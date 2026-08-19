<x-admin-layout title="Diaporama accueil">
    <p class="text-sm text-gray-500 mb-4">Ces images défilent dans la bannière de la page d'accueil, dans l'ordre indiqué. Sans image active, le logo EPA s'affiche par défaut.</p>

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.hero-slides.create') }}"
           class="inline-flex items-center px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
            + Nouvelle image
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Légende</th>
                    <th class="px-4 py-3">Ordre</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($heroSlides as $heroSlide)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ asset('storage/'.$heroSlide->image) }}" alt="" class="h-14 w-14 rounded object-cover">
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $heroSlide->caption_fr ?: '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $heroSlide->order }}</td>
                        <td class="px-4 py-3">
                            @if ($heroSlide->active)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">Actif</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600">Inactif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.hero-slides.edit', $heroSlide) }}" class="text-epa-blue hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.hero-slides.destroy', $heroSlide) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cette image ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucune image pour l'instant — le logo EPA s'affiche par défaut.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
