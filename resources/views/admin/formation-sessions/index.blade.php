<x-admin-layout title="Sessions de formation">
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.formation-sessions.create') }}"
           class="inline-flex items-center px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
            + Nouvelle session
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Formation</th>
                    <th class="px-4 py-3">Antenne</th>
                    <th class="px-4 py-3">Début</th>
                    <th class="px-4 py-3">Modalité</th>
                    <th class="px-4 py-3">Places</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($sessions as $session)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $session->formation->title_fr }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $session->antenne->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $session->start_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ str_replace('_', ' ', $session->modality) }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $session->capacity !== null ? $session->seats_taken.' / '.$session->capacity : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'ouverte' => 'bg-green-100 text-green-800',
                                    'complete' => 'bg-yellow-100 text-yellow-800',
                                    'cloturee' => 'bg-gray-100 text-gray-600',
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs {{ $statusColors[$session->status] }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.formation-sessions.edit', $session) }}" class="text-epa-blue hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.formation-sessions.destroy', $session) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cette session ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucune session pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
