<x-admin-layout title="Conversations (chat)">
    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4 flex flex-wrap gap-3 items-end">
        <div>
            <x-input-label for="search" value="Recherche dans les messages" />
            <x-text-input id="search" name="search" type="text" class="mt-1" value="{{ request('search') }}" placeholder="Mot-clé..." />
        </div>
        <button type="submit" class="px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">Filtrer</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Dernière activité</th>
                    <th class="px-4 py-3">Premier message</th>
                    <th class="px-4 py-3">Messages</th>
                    <th class="px-4 py-3">Prospect</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($conversations as $conversation)
                    @php $lead = $conversation->leadsCaptures->first(); @endphp
                    <tr>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $conversation->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-700 max-w-sm truncate">
                            {{ $conversation->messages->first()?->content ?: '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $conversation->messages_count }}</td>
                        <td class="px-4 py-3">
                            @if ($lead)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">{{ $lead->name }}</span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.assistant-conversations.show', $conversation) }}" class="text-epa-blue hover:underline">Voir</a>
                            <form method="POST" action="{{ route('admin.assistant-conversations.destroy', $conversation) }}"
                                  class="inline" onsubmit="return confirm('Supprimer définitivement cette conversation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucune conversation pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $conversations->links() }}
    </div>
</x-admin-layout>
