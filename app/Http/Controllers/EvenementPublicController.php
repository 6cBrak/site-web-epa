<?php

namespace App\Http\Controllers;

use App\Mail\EvenementInscriptionConfirmee;
use App\Models\Antenne;
use App\Models\Evenement;
use App\Models\EvenementInscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EvenementPublicController extends Controller
{
    public function index(Request $request): View
    {
        $query = Evenement::published()->with('antenne');

        if ($request->filled('antenne')) {
            $query->where('antenne_id', $request->integer('antenne'));
        }
        if ($request->filled('type') && isset(Evenement::TYPES[$request->input('type')])) {
            $query->where('type', $request->input('type'));
        }

        $upcoming = (clone $query)->upcoming()->orderBy('starts_at')->get();
        $past = (clone $query)
            ->past()
            ->orderByDesc('starts_at')
            ->take(12)
            ->get();

        return view('public.evenements.index', [
            'upcoming' => $upcoming,
            'past' => $past,
            'antennes' => Antenne::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Evenement $evenement): View
    {
        abort_unless($evenement->published, 404);

        $evenement->load(['antenne', 'sessions']);

        return view('public.evenements.show', ['evenement' => $evenement]);
    }

    public function ics(Evenement $evenement): Response
    {
        abort_unless($evenement->published, 404);

        $esc = fn (?string $v) => addcslashes(str_replace(["\r", "\n"], ['', '\\n'], (string) $v), ',;\\');
        $fmt = fn ($d) => $d->copy()->utc()->format('Ymd\THis\Z');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//EPA_BURKINA//Evenements//FR',
            'BEGIN:VEVENT',
            'UID:evenement-'.$evenement->id.'@'.parse_url(config('app.url'), PHP_URL_HOST),
            'DTSTAMP:'.$fmt(now()),
            'DTSTART:'.$fmt($evenement->starts_at),
            'DTEND:'.$fmt($evenement->ends_at ?? $evenement->starts_at->copy()->addHours(2)),
            'SUMMARY:'.$esc($evenement->title),
            'LOCATION:'.$esc($evenement->placeLabel() ?? $evenement->online_url),
            'DESCRIPTION:'.$esc(route('evenements.show', $evenement)),
            'URL:'.route('evenements.show', $evenement),
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return response(implode("\r\n", $lines)."\r\n", 200, [
            'Content-Type' => 'text/calendar; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$evenement->slug.'.ics"',
        ]);
    }

    public function register(Request $request, Evenement $evenement): RedirectResponse
    {
        abort_unless($evenement->published, 404);

        if (! $evenement->canRegister()) {
            return back()->with('error', "Les inscriptions à cet événement sont closes.");
        }

        if ($request->filled('website')) {
            return redirect()->route('evenements.show', $evenement); // honeypot anti-spam
        }

        $sessionIds = $evenement->sessions()->pluck('id');

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'profession' => ['required', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:255'],
            'epa_status' => ['required', 'in:'.implode(',', array_keys(EvenementInscription::EPA_STATUSES))],
            'epa_status_other' => ['nullable', 'required_if:epa_status,autre', 'string', 'max:255'],
            'how_heard' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'sessions' => [$sessionIds->isEmpty() ? 'nullable' : 'required', 'array'],
            'sessions.*' => ['integer', 'in:'.$sessionIds->implode(',')],
        ], [
            'sessions.required' => 'Choisissez au moins une journée de participation.',
        ]);

        $selected = $data['sessions'] ?? [];
        unset($data['sessions']);
        if ($data['epa_status'] !== 'autre') {
            $data['epa_status_other'] = null;
        }

        $normalizedPhone = preg_replace('/\D+/', '', $data['phone']);

        $inscription = DB::transaction(function () use ($evenement, $data, $selected, $normalizedPhone) {
            // Verrou sur l'événement pour éviter de dépasser les capacités en cas d'inscriptions simultanées.
            Evenement::whereKey($evenement->id)->lockForUpdate()->first();

            $duplicate = $evenement->inscriptions()->pluck('phone')
                ->contains(fn ($p) => preg_replace('/\D+/', '', $p) === $normalizedPhone);
            if ($duplicate) {
                throw ValidationException::withMessages([
                    'phone' => 'Ce numéro est déjà inscrit à cet événement.',
                ]);
            }

            foreach ($evenement->sessions()->whereIn('id', $selected)->get() as $session) {
                if ($session->isFull()) {
                    throw ValidationException::withMessages([
                        'sessions' => "« {$session->title} » est complet.",
                    ]);
                }
            }

            $inscription = $evenement->inscriptions()->create($data);
            $inscription->sessions()->sync($selected);

            return $inscription;
        });

        if ($inscription->email) {
            Mail::to($inscription->email)->queue(new EvenementInscriptionConfirmee($inscription));
        }

        return redirect()->route('evenements.confirmation', $inscription->token);
    }

    public function confirmation(string $token): View
    {
        $inscription = EvenementInscription::with(['evenement.antenne', 'sessions'])
            ->where('token', $token)
            ->firstOrFail();

        return view('public.evenements.confirmation', ['inscription' => $inscription]);
    }
}
