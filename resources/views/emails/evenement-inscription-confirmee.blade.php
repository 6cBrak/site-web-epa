<x-mail::message>
# Inscription confirmée

Bonjour {{ $inscription->full_name }},

Votre inscription à **{{ $inscription->evenement->title_fr }}** est bien enregistrée.

**Date :** {{ $inscription->evenement->starts_at->translatedFormat('l d F Y à H:i') }}
@if ($inscription->evenement->placeLabel())

**Lieu :** {{ $inscription->evenement->placeLabel() }}
@endif
@if ($inscription->sessions->isNotEmpty())

**Votre participation :** {{ $inscription->sessions->pluck('title_fr')->implode(' + ') }}
@endif

<x-mail::button :url="route('evenements.show', $inscription->evenement)">
Voir l'événement
</x-mail::button>

À très bientôt,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
