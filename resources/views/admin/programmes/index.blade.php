<x-admin-layout title="Programmes">
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.programmes.create') }}"
           class="inline-flex items-center px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
            + Nouveau programme
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Nom (FR)</th>
                    <th class="px-4 py-3">Nom (EN)</th>
                    <th class="px-4 py-3">Formations</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($programmes as $programme)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            <span class="inline-block w-2 h-2 rounded-full mr-2" style="background-color: {{ $programme->color ?? '#999' }}"></span>
                            {{ $programme->name_fr }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $programme->name_en }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $programme->formations_count }}</td>
                        <td class="px-4 py-3">
                            @if ($programme->active)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">Actif</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600">Inactif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.programmes.edit', $programme) }}" class="text-epa-blue hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.programmes.destroy', $programme) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ce programme ? Les formations rattachées seront aussi supprimées.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucun programme pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
