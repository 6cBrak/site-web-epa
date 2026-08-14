<x-admin-layout title="Formations">
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.formations.create') }}"
           class="inline-flex items-center px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
            + Nouvelle formation
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Titre (FR)</th>
                    <th class="px-4 py-3">Programme</th>
                    <th class="px-4 py-3">Durée</th>
                    <th class="px-4 py-3">Prix</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($formations as $formation)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $formation->title_fr }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $formation->programme->name_fr }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $formation->duration ?: '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $formation->price ? number_format($formation->price, 0, ',', ' ').' FCFA' : '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($formation->published)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">Publiée</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-800">Brouillon</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.formations.edit', $formation) }}" class="text-epa-blue hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.formations.destroy', $formation) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cette formation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune formation pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
