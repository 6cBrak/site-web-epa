<x-mail::message>
# Nouvelle candidature reçue

**Formation :** {{ $candidature->formation->title_fr }}
**Antenne :** {{ $candidature->antenne->name }}
**Profil :** {{ ucfirst($candidature->profile_type) }}

**Candidat :** {{ $candidature->first_name }} {{ $candidature->last_name }}
**Email :** {{ $candidature->email }}
**Téléphone :** {{ $candidature->phone }}
**Niveau d'étude :** {{ $candidature->education_level ?? '—' }}
**Ville/Pays :** {{ $candidature->city_country ?? '—' }}

@if ($candidature->comment)
**Commentaire :**
{{ $candidature->comment }}
@endif

<x-mail::button :url="url('/admin')">
Voir dans le back-office
</x-mail::button>
</x-mail::message>
