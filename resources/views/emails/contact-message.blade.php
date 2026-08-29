<x-mail::message>
# Nouveau message — formulaire de contact

**Nom :** {{ $data['name'] }}
**Email :** {{ $data['email'] }}
**Sujet :** {{ $data['subject'] }}

{{ $data['message'] }}
</x-mail::message>
