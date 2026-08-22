<x-mail::message>
# Nouveau prospect via le chat du site

@if ($lead->priority === 'chaud')
**🔥 Priorité : CHAUD — à recontacter en priorité**
@elseif ($lead->priority === 'froid')
**❄️ Priorité : froid**
@else
**🌤️ Priorité : tiède**
@endif

**Nom :** {{ $lead->name }}
**Contact :** {{ $lead->contact }}
@if ($lead->formation_interest)
**Formation d'intérêt :** {{ $lead->formation_interest }}
@endif

@if ($lead->notes)
**Notes de qualification :**
{{ $lead->notes }}
@endif

Ce prospect vient d'échanger avec l'assistant du site et a laissé ses coordonnées. Plus vite il est recontacté, plus il a de chances de se convertir en inscription.

<x-mail::button :url="route('admin.assistant-leads.show', $lead)">
Voir la conversation complète
</x-mail::button>
</x-mail::message>
