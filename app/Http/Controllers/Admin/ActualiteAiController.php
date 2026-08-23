<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ActualiteAiController extends Controller
{
    protected const DRAFT_TOOL = 'draft_actualite';

    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:1000'],
        ]);

        $apiKey = config('services.anthropic.key');

        if (! $apiKey) {
            return response()->json(['message' => "La clé API Claude n'est pas configurée."], 503);
        }

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.content_model'),
            'max_tokens' => 2000,
            'system' => $this->systemPrompt(),
            'tool_choice' => ['type' => 'tool', 'name' => self::DRAFT_TOOL],
            'tools' => [$this->draftTool()],
            'messages' => [
                ['role' => 'user', 'content' => $validated['subject']],
            ],
        ]);

        if ($response->failed()) {
            Log::error('Erreur API Claude (génération article)', ['status' => $response->status(), 'body' => $response->body()]);

            return response()->json(['message' => "Échec de la génération. Réessayez dans un instant."], 502);
        }

        $toolUse = collect($response->json('content'))->firstWhere('type', 'tool_use');

        if (! $toolUse) {
            return response()->json(['message' => "L'IA n'a pas renvoyé de brouillon exploitable. Réessayez."], 502);
        }

        return response()->json($toolUse['input']);
    }

    protected function draftTool(): array
    {
        return [
            'name' => self::DRAFT_TOOL,
            'description' => 'Fournit un brouillon d\'actualité pour le site EPA_BURKINA, en français et en anglais.',
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'title_fr' => ['type' => 'string', 'description' => 'Titre accrocheur en français, moins de 100 caractères.'],
                    'title_en' => ['type' => 'string', 'description' => 'Traduction anglaise naturelle du titre (pas littérale mot à mot).'],
                    'excerpt_fr' => ['type' => 'string', 'description' => 'Résumé en français, 1 à 2 phrases, pour les vignettes de la liste des actualités.'],
                    'excerpt_en' => ['type' => 'string', 'description' => 'Traduction anglaise du résumé.'],
                    'content_fr' => ['type' => 'string', 'description' => "Corps de l'article en français, plusieurs paragraphes, ton institutionnel mais chaleureux."],
                    'content_en' => ['type' => 'string', 'description' => "Traduction anglaise du corps de l'article."],
                ],
                'required' => ['title_fr', 'title_en', 'excerpt_fr', 'excerpt_en', 'content_fr', 'content_en'],
            ],
        ];
    }

    protected function systemPrompt(): string
    {
        return <<<'PROMPT'
Tu es le rédacteur des actualités du site web d'EPA_BURKINA, un centre de formation professionnelle en Informatique et Action Humanitaire au Burkina Faso (antennes à Ouagadougou, Bobo-Dioulasso et Dori/Sahel).

TÂCHE : à partir du sujet/brief donné par l'équipe EPA (souvent bref, quelques mots ou phrases), rédige un brouillon d'actualité complet en français ET en anglais, prêt à être relu et publié par l'équipe.

TON : institutionnel mais chaleureux et accessible — un centre de formation qui parle à de jeunes professionnels et à ses partenaires, pas un communiqué froid. Phrases claires, pas de jargon inutile.

LIMITES STRICTES :
- N'invente JAMAIS de chiffres précis, dates exactes, noms de personnes ou statistiques qui ne sont pas mentionnés dans le sujet fourni. Si le brief ne précise pas une date ou un chiffre, reste général (« récemment », « plusieurs apprenants », etc.) plutôt que d'inventer une précision.
- Ne prétends jamais qu'un événement a eu lieu avec des détails logistiques précis (lieu exact, heure) sauf si le brief les donne.
- Le brouillon sera relu par un humain avant publication : mieux vaut rester sobre sur les détails non fournis que d'inventer quelque chose de faux.

FORMAT :
- title_fr / title_en : titre court et accrocheur.
- excerpt_fr / excerpt_en : résumé de 1-2 phrases pour les vignettes de la page Actualités.
- content_fr / content_en : corps de l'article, 3 à 5 paragraphes courts, texte brut (pas de markdown, pas de titres HTML — juste des paragraphes séparés par des sauts de ligne).
- L'anglais doit être une vraie traduction naturelle, pas mot à mot.

Réponds uniquement via l'outil fourni, jamais en texte libre.
PROMPT;
    }
}
