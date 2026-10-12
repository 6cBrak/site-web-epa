<x-admin-layout :title="'Inscrits — '.$evenement->title_fr">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <a href="{{ route('admin.evenements.index') }}" class="text-sm text-gray-500 hover:text-epa-red">&larr; Tous les événements</a>
        <a href="{{ route('admin.evenements.inscriptions.export', $evenement) }}"
           class="inline-flex items-center px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
            Exporter en CSV
        </a>
    </div>

    <div class="grid md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs uppercase text-gray-500">Inscrits</div>
            <div class="text-3xl font-bold">{{ $inscriptions->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs uppercase text-gray-500 mb-1">Par journée</div>
            @forelse ($evenement->sessions as $session)
                <div class="text-sm flex justify-between gap-2">
                    <span class="truncate">{{ $session->title_fr }}</span>
                    <span class="font-semibold">{{ $stats['sessions'][$session->title_fr] ?? 0 }}{{ $session->capacity ? ' / '.$session->capacity : '' }}</span>
                </div>
            @empty
                <div class="text-sm text-gray-400">—</div>
            @endforelse
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs uppercase text-gray-500 mb-1">Villes</div>
            @forelse ($stats['cities'] as $label => $count)
                <div class="text-sm flex justify-between gap-2"><span class="truncate">{{ $label }}</span><span class="font-semibold">{{ $count }}</span></div>
            @empty
                <div class="text-sm text-gray-400">—</div>
            @endforelse
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs uppercase text-gray-500 mb-1">Sources</div>
            @forelse ($stats['sources'] as $label => $count)
                <div class="text-sm flex justify-between gap-2"><span class="truncate">{{ $label }}</span><span class="font-semibold">{{ $count }}</span></div>
            @empty
                <div class="text-sm text-gray-400">—</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Participant</th>
                    <th class="px-4 py-3">Contact</th>
                    <th class="px-4 py-3">Ville</th>
                    <th class="px-4 py-3">Statut EPA</th>
                    <th class="px-4 py-3">Participation</th>
                    <th class="px-4 py-3">Source</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($inscriptions as $i)
                    <tr class="align-top">
                        <td class="px-4 py-3 whitespace-nowrap">{{ $i->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $i->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $i->profession }}{{ $i->nationality ? ' · '.$i->nationality : '' }}</div>
                            @if ($i->comment)
                                <div class="text-xs text-gray-500 mt-1 italic max-w-xs">« {{ $i->comment }} »</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ $i->whatsappUrl('Bonjour '.$i->full_name.', EPA_BURKINA à propos de : '.$evenement->title_fr) }}"
                               target="_blank" rel="noopener" class="text-green-700 hover:underline">{{ $i->phone }} (WhatsApp)</a>
                            @if ($i->email)
                                <div class="text-xs text-gray-500">{{ $i->email }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $i->city }}</td>
                        <td class="px-4 py-3">{{ $i->epa_status_label }}</td>
                        <td class="px-4 py-3">{{ $i->sessions->pluck('title_fr')->implode(', ') ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $i->how_heard }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.evenement-inscriptions.destroy', $i) }}"
                                  onsubmit="return confirm('Supprimer cette inscription ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">Aucune inscription pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
