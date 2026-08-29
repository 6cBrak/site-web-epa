<?php

namespace App\Http\Controllers;

use App\Mail\CandidatureConfirmee;
use App\Mail\NouvelleCandidature;
use App\Models\Antenne;
use App\Models\AssistantLeadCapture;
use App\Models\Candidature;
use App\Models\Formation;
use App\Models\FormationSession;
use App\Models\PromoCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CandidatureController extends Controller
{
    public function create(Request $request): View
    {
        $sessions = FormationSession::where('status', 'ouverte')
            ->where('start_date', '>=', now()->subDay())
            ->with('antenne')
            ->orderBy('start_date')
            ->get()
            ->groupBy('formation_id')
            ->map(fn ($group) => $group->map(fn ($session) => [
                'id' => $session->id,
                'label' => $session->start_date->translatedFormat('d F Y').' — '.$session->antenne->name,
            ])->values());

        return view('public.candidatures.create', [
            'formations' => Formation::where('published', true)->orderBy('title_fr')->get(),
            'antennes' => Antenne::where('active', true)->orderBy('name')->get(),
            'selectedFormationId' => Formation::where('slug', $request->query('formation'))->value('id'),
            'sessionsByFormation' => $sessions,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'formation_id' => ['required', 'exists:formations,id'],
            'formation_session_id' => ['nullable', 'exists:formation_sessions,id'],
            'antenne_id' => ['required', 'exists:antennes,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'education_level' => ['nullable', 'string', 'max:100'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'city_country' => ['nullable', 'string', 'max:255'],
            'profile_type' => ['required', 'in:etudiant,professionnel'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:4096', 'required_if:profile_type,professionnel'],
            'start_preference' => ['nullable', 'in:immediat,prochaine_rentree,session_specialisee'],
            'how_heard' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string'],
            'promo_code' => ['nullable', 'string', 'max:50'],
        ]);

        if (! empty($data['promo_code'])) {
            $promoCode = PromoCode::where('code', $data['promo_code'])->first();

            if (! $promoCode || ! $promoCode->isUsable()) {
                throw ValidationException::withMessages([
                    'promo_code' => "Ce code promo n'est pas valide ou n'est plus utilisable.",
                ]);
            }

            $data['promo_code_id'] = $promoCode->id;
        }
        unset($data['promo_code']);

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('cv', 'public');
        }

        $data['status'] = 'nouvelle';

        $matchingLead = AssistantLeadCapture::where('contact', $data['email'])
            ->orWhere('contact', $data['phone'])
            ->latest('captured_at')
            ->first();

        if ($matchingLead) {
            $data['assistant_lead_capture_id'] = $matchingLead->id;
        }

        $candidature = Candidature::create($data);

        if ($matchingLead) {
            $matchingLead->update(['status' => 'converti']);
        }

        $recipient = Antenne::find($data['antenne_id'])?->email;

        Mail::to($candidature->email)->queue(new CandidatureConfirmee($candidature));

        if ($recipient) {
            Mail::to($recipient)->queue(new NouvelleCandidature($candidature));
        }

        return redirect()->route('candidatures.confirmation', $candidature->tracking_token);
    }

    public function confirmation(Candidature $candidature): View
    {
        return view('public.candidatures.confirmation', ['candidature' => $candidature]);
    }

    public function track(string $token): View
    {
        $candidature = Candidature::with(['formation', 'antenne'])
            ->where('tracking_token', $token)
            ->firstOrFail();

        return view('public.candidatures.track', ['candidature' => $candidature]);
    }
}
