<x-admin-layout title="Antennes">
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.antennes.create') }}"
           class="inline-flex items-center px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
            + Nouvelle antenne
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Adresse</th>
                    <th class="px-4 py-3">Téléphone</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($antennes as $antenne)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $antenne->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $antenne->address }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $antenne->phone }}</td>
                        <td class="px-4 py-3">
                            @if ($antenne->active)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.antennes.edit', $antenne) }}" class="text-epa-blue hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.antennes.destroy', $antenne) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cette antenne ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucune antenne pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
