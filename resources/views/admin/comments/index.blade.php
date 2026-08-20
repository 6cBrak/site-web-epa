<x-admin-layout title="Commentaires">
    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4 flex flex-wrap gap-3 items-end">
        <div>
            <x-input-label for="status" value="Statut" />
            <select id="status" name="status" class="mt-1 border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                <option value="">Tous</option>
                <option value="attente" @selected(request('status') === 'attente')>En attente</option>
                <option value="approuve" @selected(request('status') === 'approuve')>Approuvés</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">Filtrer</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Auteur</th>
                    <th class="px-4 py-3">Article</th>
                    <th class="px-4 py-3">Commentaire</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($comments as $comment)
                    <tr>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $comment->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $comment->author_name }}</div>
                            <div class="text-xs text-gray-500">{{ $comment->author_email }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            @if ($comment->actualite)
                                <a href="{{ route('actualites.show', $comment->actualite) }}" target="_blank" class="hover:underline">
                                    {{ $comment->actualite->title_fr }}
                                </a>
                            @else
                                <span class="text-gray-400">Article supprimé</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-700 max-w-sm">{{ Str::limit($comment->body, 150) }}</td>
                        <td class="px-4 py-3">
                            @if ($comment->approved)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">Approuvé</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-800">En attente</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            @unless ($comment->approved)
                                <form method="POST" action="{{ route('admin.comments.update', $comment) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-green-700 hover:underline mr-3">Approuver</button>
                                </form>
                            @endunless
                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="inline" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun commentaire pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $comments->links() }}
    </div>
</x-admin-layout>
