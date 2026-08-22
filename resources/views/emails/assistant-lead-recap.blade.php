@php
    $antenne = \App\Models\Antenne::where('active', true)->orderBy('name')->first();

    $nameParts = preg_split('/\s+/', trim($lead->name), 2);
    $inscriptionUrl = route('candidatures.create', array_filter([
        'first_name' => $nameParts[0] ?? null,
        'last_name' => $nameParts[1] ?? null,
        'email' => filter_var($lead->contact, FILTER_VALIDATE_EMAIL) ? $lead->contact : null,
        'phone' => ! filter_var($lead->contact, FILTER_VALIDATE_EMAIL) ? $lead->contact : null,
    ]));

    $whatsappUrl = null;
    if ($antenne && $antenne->phone) {
        $waNumber = preg_replace('/\D/', '', $antenne->phone);
        $waText = "Bonjour, je suis {$lead->name}. J'ai discuté avec l'assistant du site à propos de : "
            .($lead->formation_interest ?: 'vos formations').'.';
        $whatsappUrl = 'https://wa.me/'.$waNumber.'?text='.urlencode($waText);
    }
@endphp
<x-mail::message>
# On vous a bien noté(e), {{ $lead->name }} ! 🎉

Merci d'avoir échangé avec l'assistant d'EPA_BURKINA.

@if ($lead->formation_interest)
**Formation qui vous intéresse :** {{ $lead->formation_interest }}
@endif

@if ($lead->notes)
{{ $lead->notes }}
@endif

Notre équipe va revenir vers vous très prochainement pour vous accompagner dans votre projet de formation.

En attendant, vous pouvez démarrer votre inscription en ligne dès maintenant (vos informations sont déjà pré-remplies) :

<x-mail::button :url="$inscriptionUrl">
S'inscrire en ligne
</x-mail::button>

@if ($whatsappUrl)
Une question avant qu'on vous recontacte ? Continuez directement sur WhatsApp :

<x-mail::button :url="$whatsappUrl" color="success">
Discuter sur WhatsApp
</x-mail::button>
@endif

@if ($antenne)
- **WhatsApp/Téléphone :** {{ $antenne->phone }}
- **Email :** {{ $antenne->email }}
@endif

À très bientôt,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
