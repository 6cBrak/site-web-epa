<x-admin-layout title="Événements">
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.evenements.create') }}"
           class="inline-flex items-center px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
            + Nouvel événement
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Événement</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Lieu</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Inscrits</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($evenements as $evenement)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $evenement->title_fr }}</div>
                            <div class="text-xs text-gray-500">{{ $evenement->type_label }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $evenement->starts_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $evenement->placeLabel() ?? '—' }}</td>
                        <td class="px-4 py-3 space-x-1">
                            @if (! $evenement->published)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-800">Brouillon</span>
                            @elseif ($evenement->isPast())
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600">Terminé</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">À venir</span>
                            @endif
                            @unless ($evenement->registration_open)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700">Inscr. fermées</span>
                            @endunless
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.evenements.inscriptions', $evenement) }}" class="text-epa-blue hover:underline">
                                {{ $evenement->inscriptions_count }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.evenements.inscriptions', $evenement) }}" class="text-epa-blue hover:underline">Inscrits</a>
                            <a href="{{ route('admin.evenements.edit', $evenement) }}" class="text-epa-blue hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.evenements.destroy', $evenement) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cet événement et toutes ses inscriptions ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun événement pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
