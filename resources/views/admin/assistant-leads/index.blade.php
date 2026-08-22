<x-admin-layout title="Prospects (chat)">
    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4 flex flex-wrap gap-3 items-end">
        <div>
            <x-input-label for="search" value="Recherche" />
            <x-text-input id="search" name="search" type="text" class="mt-1" value="{{ request('search') }}" placeholder="Nom, contact, formation..." />
        </div>
        <div>
            <x-input-label for="status" value="Statut" />
            <select id="status" name="status" class="mt-1 border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                <option value="">Tous</option>
                @foreach (['nouveau' => 'Nouveau', 'contacte' => 'Contacté', 'converti' => 'Converti', 'perdu' => 'Perdu'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">Filtrer</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Prospect</th>
                    <th class="px-4 py-3">Formation</th>
                    <th class="px-4 py-3">Notes</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $statusColors = [
                        'nouveau' => 'bg-blue-100 text-blue-800',
                        'contacte' => 'bg-yellow-100 text-yellow-800',
                        'converti' => 'bg-green-100 text-green-800',
                        'perdu' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                @forelse ($leads as $lead)
                    <tr>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $lead->captured_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $lead->name }}</div>
                            <div class="text-xs text-gray-500">{{ $lead->contact }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $lead->formation_interest ?: '—' }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs max-w-xs truncate" title="{{ $lead->notes }}">{{ $lead->notes ?: '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs {{ $statusColors[$lead->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.assistant-leads.show', $lead) }}" class="text-epa-blue hover:underline">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun prospect capturé par le chat pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $leads->links() }}
    </div>
</x-admin-layout>
