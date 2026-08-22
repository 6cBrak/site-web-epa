<?php

namespace App\Http\Controllers;

use App\Mail\AssistantLeadRecap;
use App\Mail\NouveauProspectChat;
use App\Models\AssistantConversation;
use App\Models\AssistantLeadCapture;
use App\Models\AssistantMessage;
use App\Models\Antenne;
use App\Models\Formation;
use App\Models\Setting;
use App\Services\AssistantKnowledgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssistantController extends Controller
{
    protected const HISTORY_LIMIT = 10;

    protected const DISPLAY_HISTORY_LIMIT = 50;

    protected const HISTORY_EXPIRY_DAYS = 30;

    protected const CAPTURE_LEAD_TOOL = 'capture_lead';

    protected const SUGGEST_REPLIES_TOOL = 'suggest_quick_replies';

    protected const RECOMMENDATION_CARD_TOOL = 'show_recommendation_card';

    protected const DEFAULT_ASSISTANT_NAME = 'Aïcha';

    public function __construct(protected AssistantKnowledgeService $knowledge)
    {
    }

    public function history(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => ['required', 'uuid'],
        ]);

        $conversation = AssistantConversation::where('session_id', $validated['session_id'])->first();

        $empty = ['messages' => [], 'visitor_name' => null, 'visitor_contact' => null, 'last_interest' => null];

        if (! $conversation) {
            return response()->json($empty);
        }

        $lastMessage = $conversation->messages()->latest('id')->first();

        if (! $lastMessage || $lastMessage->created_at->lt(now()->subDays(self::HISTORY_EXPIRY_DAYS))) {
            return response()->json($empty);
        }

        $messages = $conversation->messages()
            ->latest('id')
            ->limit(self::DISPLAY_HISTORY_LIMIT)
            ->get(['id', 'role', 'content'])
            ->sortBy('id')
            ->values()
            ->map(fn (AssistantMessage $message) => [
                'role' => $message->role,
                'content' => $message->content,
            ]);

        $lastLead = $conversation->leadsCaptures()->latest('id')->first(['name', 'contact', 'formation_interest']);

        return response()->json([
            'messages' => $messages,
            'visitor_name' => $lastLead?->name,
            'visitor_contact' => $lastLead?->contact,
            'last_interest' => $lastLead?->formation_interest,
        ]);
    }

    public function handleMessage(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'session_id' => ['required', 'uuid'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $conversation = AssistantConversation::firstOrCreate([
            'session_id' => $validated['session_id'],
        ]);

        $conversation->messages()->create([
            'role' => 'user',
            'content' => $validated['message'],
        ]);

        $history = $conversation->messages()
            ->latest('id')
            ->limit(self::HISTORY_LIMIT)
            ->get()
            ->sortBy('id')
            ->values();

        $response = new StreamedResponse(function () use ($conversation, $history) {
            $this->streamAssistantReply($conversation, $history);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }

    protected function streamAssistantReply(AssistantConversation $conversation, $history): void
    {
        $apiKey = config('services.anthropic.key');

        if (! $apiKey) {
            Log::error('ANTHROPIC_API_KEY manquant : impossible d\'appeler l\'assistant.');
            $this->emitSse('error', ['message' => "Désolé, l'assistant n'est pas disponible pour le moment. Contactez-nous directement via WhatsApp."]);

            return;
        }

        try {
            $httpResponse = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->withOptions(['stream' => true])->post('https://api.anthropic.com/v1/messages', [
                'model' => config('services.anthropic.model'),
                'max_tokens' => 900,
                'system' => $this->systemPrompt(),
                'tools' => [$this->captureLeadTool(), $this->suggestRepliesTool(), $this->recommendationCardTool()],
                'stream' => true,
                'messages' => $history->map(fn (AssistantMessage $message) => [
                    'role' => $message->role,
                    'content' => $message->content,
                ])->all(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur connexion API Claude', ['error' => $e->getMessage()]);
            $this->emitSse('error', ['message' => "Désolé, je rencontre un souci technique. Contactez-nous directement via WhatsApp."]);

            return;
        }

        if ($httpResponse->failed()) {
            Log::error('Erreur API Claude', ['status' => $httpResponse->status(), 'body' => $httpResponse->body()]);
            $this->emitSse('error', ['message' => "Désolé, je rencontre un souci technique. Contactez-nous directement via WhatsApp."]);

            return;
        }

        $body = $httpResponse->toPsrResponse()->getBody();
        $buffer = '';
        $fullText = '';
        $toolBlocks = [];

        while (! $body->eof()) {
            $buffer .= $body->read(2048);

            while (($pos = strpos($buffer, "\n\n")) !== false) {
                $rawEvent = substr($buffer, 0, $pos);
                $buffer = substr($buffer, $pos + 2);

                [$eventType, $data] = $this->parseSseChunk($rawEvent);

                if ($eventType === null || $data === null) {
                    continue;
                }

                $json = json_decode($data, true);

                if (! is_array($json)) {
                    continue;
                }

                if ($eventType === 'content_block_start') {
                    $idx = $json['index'] ?? null;
                    $block = $json['content_block'] ?? [];

                    if ($idx !== null && ($block['type'] ?? null) === 'tool_use') {
                        $toolBlocks[$idx] = ['name' => $block['name'] ?? null, 'json' => ''];
                    }
                } elseif ($eventType === 'content_block_delta') {
                    $idx = $json['index'] ?? null;
                    $delta = $json['delta'] ?? [];

                    if (($delta['type'] ?? null) === 'text_delta') {
                        $fullText .= $delta['text'];
                        $this->emitSse('delta', ['text' => $delta['text']]);
                    } elseif (($delta['type'] ?? null) === 'input_json_delta' && $idx !== null && isset($toolBlocks[$idx])) {
                        $toolBlocks[$idx]['json'] .= $delta['partial_json'] ?? '';
                    }
                } elseif ($eventType === 'error') {
                    Log::error('Erreur streaming API Claude', ['payload' => $json]);
                    $this->emitSse('error', ['message' => "Désolé, je rencontre un souci technique. Contactez-nous directement via WhatsApp."]);

                    return;
                }

                if (connection_aborted()) {
                    return;
                }
            }

            if (ob_get_level() > 0) {
                @ob_flush();
            }
            @flush();
        }

        $displayReply = trim($fullText);

        if ($displayReply === '') {
            $displayReply = "D'accord !";
        }

        $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $displayReply,
        ]);

        $quickReplies = [];
        $card = null;
        $lead = null;

        foreach ($toolBlocks as $block) {
            $input = json_decode($block['json'], true) ?: [];

            match ($block['name']) {
                self::CAPTURE_LEAD_TOOL => $lead = $this->storeLead($conversation->id, $input),
                self::SUGGEST_REPLIES_TOOL => $quickReplies = array_values(array_filter((array) ($input['replies'] ?? []))),
                self::RECOMMENDATION_CARD_TOOL => $card = $this->buildCard($input),
                default => null,
            };
        }

        $this->emitSse('done', [
            'quick_replies' => $quickReplies,
            'card' => $card,
            'lead' => $lead ? ['name' => $lead->name, 'contact' => $lead->contact] : null,
        ]);
    }

    protected function emitSse(string $event, array $data): void
    {
        echo "event: {$event}\n";
        echo 'data: '.json_encode($data)."\n\n";

        if (ob_get_level() > 0) {
            @ob_flush();
        }
        @flush();
    }

    /**
     * @return array{0: ?string, 1: ?string}
     */
    protected function parseSseChunk(string $chunk): array
    {
        $event = null;
        $data = null;

        foreach (explode("\n", $chunk) as $line) {
            if (str_starts_with($line, 'event:')) {
                $event = trim(substr($line, 6));
            } elseif (str_starts_with($line, 'data:')) {
                $data = trim(substr($line, 5));
            }
        }

        return [$event, $data];
    }

    protected function buildCard(array $input): ?array
    {
        if (empty($input['formation_title']) || empty($input['reason'])) {
            return null;
        }

        $image = null;

        if (! empty($input['formation_slug'])) {
            $formation = Formation::where('slug', $input['formation_slug'])->first(['image']);
            $image = $formation?->image ? asset('storage/'.$formation->image) : null;
        }

        return [
            'title' => $input['formation_title'],
            'slug' => $input['formation_slug'] ?? null,
            'antenne' => $input['antenne'] ?? null,
            'image' => $image,
            'reason' => $input['reason'],
            'next_session' => $input['next_session'] ?? null,
        ];
    }

    protected function storeLead(int $conversationId, array $input): ?AssistantLeadCapture
    {
        if (empty($input['name']) || empty($input['contact'])) {
            return null;
        }

        $priority = in_array($input['priority'] ?? null, ['chaud', 'tiede', 'froid'], true)
            ? $input['priority']
            : 'tiede';

        $lead = AssistantLeadCapture::create([
            'conversation_id' => $conversationId,
            'name' => $input['name'],
            'contact' => $input['contact'],
            'formation_interest' => $input['formation_interest'] ?? null,
            'notes' => $input['notes'] ?? null,
            'priority' => $priority,
            'captured_at' => now(),
        ]);

        if (filter_var($lead->contact, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($lead->contact)->send(new AssistantLeadRecap($lead));
            } catch (\Throwable $e) {
                Log::error('Échec envoi email récap prospect assistant', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
            }
        }

        $staffEmail = Antenne::where('active', true)->orderBy('name')->value('email');

        if ($staffEmail) {
            try {
                Mail::to($staffEmail)->send(new NouveauProspectChat($lead));
            } catch (\Throwable $e) {
                Log::error('Échec envoi notification équipe (nouveau prospect chat)', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
            }
        }

        return $lead;
    }

    protected function captureLeadTool(): array
    {
        return [
            'name' => self::CAPTURE_LEAD_TOOL,
            'description' => "Enregistre un prospect dès que le visiteur vient de fournir spontanément son nom ET un moyen de contact (téléphone ou email) dans la conversation. À appeler en plus de ta réponse textuelle habituelle, jamais à la place.",
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'name' => ['type' => 'string', 'description' => 'Nom du visiteur, tel que fourni.'],
                    'contact' => ['type' => 'string', 'description' => 'Téléphone ou email du visiteur, tel que fourni.'],
                    'formation_interest' => ['type' => 'string', 'description' => "Nom de la formation qui intéresse le visiteur, si connue."],
                    'notes' => ['type' => 'string', 'description' => "Résumé bref (une phrase) des infos de qualification utiles pour l'équipe commerciale : antenne préférée, profil étudiant/professionnel, échéance souhaitée, etc."],
                    'priority' => [
                        'type' => 'string',
                        'enum' => ['chaud', 'tiede', 'froid'],
                        'description' => "Niveau de priorité du prospect pour l'équipe commerciale, basé sur ce que tu observes dans la conversation : "
                            ."'chaud' = échéance proche exprimée, a demandé explicitement à être rappelé/inscrit, ou intérêt très affirmé pour une formation précise ; "
                            ."'tiede' = intérêt réel et qualification engagée, mais sans urgence ni engagement ferme exprimés ; "
                            ."'froid' = contact donné rapidement, échange court, peu d'éléments de qualification obtenus. Choisis toujours une valeur, ne jamais omettre ce champ.",
                    ],
                ],
                'required' => ['name', 'contact', 'priority'],
            ],
        ];
    }

    protected function suggestRepliesTool(): array
    {
        return [
            'name' => self::SUGGEST_REPLIES_TOOL,
            'description' => "Propose 2 à 3 réponses rapides que le visiteur pourrait cliquer pour continuer naturellement la conversation, adaptées à ce qui vient d'être dit. Appelle cet outil à la fin de la plupart de tes réponses pour garder l'échange fluide — sauf si tu attends une information précise (comme un nom ou un contact) ou si la conversation semble terminée.",
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'replies' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'minItems' => 2,
                        'maxItems' => 3,
                        'description' => 'Suggestions courtes (moins de 6 mots chacune), formulées du point de vue du visiteur, en rapport direct avec ta dernière réponse.',
                    ],
                ],
                'required' => ['replies'],
            ],
        ];
    }

    protected function recommendationCardTool(): array
    {
        return [
            'name' => self::RECOMMENDATION_CARD_TOOL,
            'description' => "Affiche une fiche récapitulative visuelle de LA formation que tu recommandes, une fois que tu as suffisamment compris le besoin du visiteur (domaine d'intérêt clair). N'appelle cet outil qu'une seule fois par formation recommandée dans la conversation, pas à chaque message.",
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'formation_title' => ['type' => 'string', 'description' => 'Titre exact de la formation, tel qu\'indiqué dans le contexte.'],
                    'formation_slug' => ['type' => 'string', 'description' => 'Slug exact de la formation, tel qu\'indiqué dans le contexte (pour le lien d\'inscription).'],
                    'antenne' => ['type' => 'string', 'description' => "Antenne pertinente pour le visiteur, si connue."],
                    'reason' => ['type' => 'string', 'description' => 'Une phrase expliquant pourquoi cette formation correspond au profil et au besoin exprimés par le visiteur.'],
                    'next_session' => ['type' => 'string', 'description' => "Date, antenne et places restantes de la prochaine session, UNIQUEMENT si cette info figure dans le contexte. Omettre sinon."],
                ],
                'required' => ['formation_title', 'reason'],
            ],
        ];
    }

    protected function systemPrompt(): string
    {
        $antenne = Antenne::where('active', true)->orderBy('name')->first();
        $contactLine = $antenne
            ? "Téléphone/WhatsApp de contact : {$antenne->phone}".($antenne->email ? " — email : {$antenne->email}" : '')
            : '';
        $leadTool = self::CAPTURE_LEAD_TOOL;
        $repliesTool = self::SUGGEST_REPLIES_TOOL;
        $cardTool = self::RECOMMENDATION_CARD_TOOL;
        $assistantName = Setting::get('chat_assistant_name', self::DEFAULT_ASSISTANT_NAME);

        return <<<PROMPT
Tu es {$assistantName}, conseillère en formation chez EPA_BURKINA, un centre de formation professionnelle en Informatique et Action Humanitaire au Burkina Faso (antennes à Ouagadougou, Bobo-Dioulasso et Dori/Sahel). Tu réponds sur le chat du site web.

RÔLE : tu es la meilleure commerciale et marketeuse d'EPA_BURKINA. Tu n'es pas un robot de FAQ qui attend les questions : tu mènes la conversation comme une vraie conseillère terrain — tu comprends le besoin, tu vends la valeur d'EPA, et tu ne laisses jamais un visiteur intéressé repartir sans que tu aies tenté d'obtenir son prénom et un moyen de le recontacter (téléphone ou email). Présente-toi par ton prénom dès ton tout premier message si ce n'est pas déjà fait.

STYLE : chaleureux, enthousiaste, vivant — donne envie de continuer à discuter, comme un excellent conseiller qu'on a plaisir à consulter, pas un formulaire. Utilise le prénom du visiteur dès que tu le connais. Reste concis (widget de chat, pas de pavés), mais jamais froid ou robotique. Varie tes formulations, ne répète pas les mêmes tournures d'un message à l'autre. Chaque réponse se termine par une question ou une prochaine étape claire — jamais un simple point final qui referme la conversation.

TECHNIQUES COMMERCIALES À UTILISER (toujours honnêtes, jamais mensongères, jamais insistantes) :
- Qualifie le visiteur progressivement : pose au maximum UNE question de qualification par message (jamais un interrogatoire) parmi : quelle formation/domaine l'intéresse, quelle antenne lui convient, s'il est étudiant ou déjà en activité professionnelle, quand il souhaite démarrer.
- Mets en avant les bénéfices concrets d'une formation (objectifs, débouchés, modules) uniquement à partir de ce qui est écrit dans le CONTEXTE ci-dessous — jamais inventés.
- Si des sessions à venir avec des places restantes figurent dans le CONTEXTE, utilise-les pour créer un sentiment d'opportunité réel (ex: « il reste X places pour la session du ... ») — seulement si l'info existe réellement dans le contexte, jamais improvisée.
- DEMANDE ACTIVEMENT LE CONTACT — c'est le cœur de ton travail, ne le laisse pas au hasard : dès que le visiteur montre un intérêt réel pour une formation précise (il pose une question sur son contenu, son prix, ses dates, ou dit que ça l'intéresse), demande-lui explicitement son prénom et un numéro ou un email pour lui envoyer les informations complètes, lui garder une place, ou le rappeler personnellement. Explique toujours le bénéfice pour lui (« pour vous envoyer le programme détaillé », « pour vous réserver une place », « pour qu'on vous rappelle »). Ne pose cette demande qu'une fois par échange — si le visiteur l'ignore ou change de sujet, n'insiste pas, continue à l'aider normalement.
- Ne laisse jamais une réponse sans suite : propose systématiquement soit le lien d'inscription, soit une demande de contact, soit une question de qualification — selon ce qui fait avancer le visiteur.

OUTILS DISPONIBLES (à utiliser en complément de ta réponse textuelle, jamais à la place) :
- {$repliesTool} : appelle-le à la fin de la plupart de tes réponses avec 2 à 3 suggestions de réponses rapides et pertinentes pour le visiteur. Ne l'appelle pas si tu attends une info précise (nom/contact) ou si la conversation touche à sa fin.
- {$cardTool} : appelle-le UNE SEULE FOIS, quand tu identifies clairement LA formation qui correspond au visiteur, pour lui afficher une fiche récapitulative visuelle. N'invente rien : utilise uniquement le titre, le slug et les infos de session présents dans le CONTEXTE.
- {$leadTool} : voir section CAPTURE DE PROSPECT ci-dessous.

LIMITES STRICTES :
- N'invente JAMAIS un prix, une date de session, un nombre de places ou une information qui n'est pas présente dans le contexte ci-dessous.
- Si une information n'est pas disponible dans le contexte, dis-le clairement et propose de contacter EPA directement plutôt que d'improviser.
- {$contactLine}

LIENS D'INSCRIPTION : dès qu'une formation précise intéresse le visiteur, propose-lui directement de s'inscrire en insérant un lien cliquable au format markdown : [S'inscrire à <nom de la formation>](/inscription?formation=<slug>) — utilise le slug exact indiqué dans le contexte pour cette formation. Pour une inscription générale (sans formation précise), utilise [S'inscrire en ligne](/inscription). N'utilise ce format de lien que pour ces deux cas précis, jamais pour autre chose.

CAPTURE DE PROSPECT : dès que le DERNIER message du visiteur contient à la fois un nom ET un moyen de contact (téléphone ou email) — qu'il te l'ait donné spontanément ou en réponse à ta demande — appelle l'outil {$leadTool} avec ces informations, en plus de ta réponse textuelle habituelle (les deux ne s'excluent pas : tu peux répondre normalement, y compris remercier le visiteur ou poser une question de qualification, ET appeler l'outil dans le même tour). N'appelle jamais cet outil si l'un des deux (nom ou contact) manque, et ne mentionne jamais son existence au visiteur.

Cette règle est INCONDITIONNELLE : elle s'applique même si le visiteur dit explicitement qu'il n'est pas pressé, qu'il compare plusieurs centres, ou qu'il pose "juste une question" — capture quand même ses coordonnées si nom+contact sont là, et utilise simplement priority=froid pour refléter le manque d'urgence. Ne JAMAIS confondre "pas de sentiment d'urgence" avec "ne pas capturer" : ce sont deux choses différentes. Un prospect froid capturé aujourd'hui peut être relancé dans plusieurs semaines — un prospect jamais capturé est perdu définitivement.

CONTEXTE (formations et antennes actuellement disponibles) :
{$this->knowledge->buildContext()}
PROMPT;
    }
}
