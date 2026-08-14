<x-mail::message>
# Candidature bien reçue

Bonjour {{ $candidature->first_name }},

Nous avons bien reçu votre candidature pour la formation **{{ $candidature->formation->title_fr }}**
à l'antenne **{{ $candidature->antenne->name }}**.

Notre équipe va l'examiner et reviendra vers vous rapidement.

Vous pouvez suivre l'état de votre candidature à tout moment via ce lien :

<x-mail::button :url="route('candidatures.track', $candidature->tracking_token)">
Suivre ma candidature
</x-mail::button>

Merci de votre confiance,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
