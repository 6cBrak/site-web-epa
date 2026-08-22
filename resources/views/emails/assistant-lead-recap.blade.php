@php
    $antenne = \App\Models\Antenne::where('active', true)->orderBy('name')->first();
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

En attendant, vous pouvez démarrer votre inscription en ligne dès maintenant :

<x-mail::button :url="route('candidatures.create')">
S'inscrire en ligne
</x-mail::button>

Une question avant qu'on vous recontacte ?
@if ($antenne)
- **WhatsApp/Téléphone :** {{ $antenne->phone }}
- **Email :** {{ $antenne->email }}
@endif

À très bientôt,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
