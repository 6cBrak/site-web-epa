<x-admin-layout title="Conversation">
    <a href="{{ route('admin.assistant-conversations.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Retour à la liste</a>

    <div class="mt-4 grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4">Transcript</h3>
            <div class="space-y-3">
                @forelse ($conversation->messages as $message)
                    <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] px-3 py-2 rounded-lg text-sm whitespace-pre-line
                            {{ $message->role === 'user' ? 'bg-epa-red text-white' : 'bg-gray-100 text-gray-800' }}">
                            {{ $message->content }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aucun message enregistré.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6 text-sm space-y-2">
                <h3 class="font-semibold mb-2">Infos</h3>
                <div><span class="text-gray-500">Démarrée le</span><br>{{ $conversation->created_at->format('d/m/Y à H:i') }}</div>
                <div><span class="text-gray-500">Dernière activité</span><br>{{ $conversation->updated_at->format('d/m/Y à H:i') }}</div>
                <div><span class="text-gray-500">Messages</span><br>{{ $conversation->messages->count() }}</div>
            </div>

            @if ($conversation->leadsCaptures->isNotEmpty())
                <div class="bg-white rounded-lg shadow p-6 text-sm space-y-2">
                    <h3 class="font-semibold mb-2">Prospect capturé</h3>
                    @foreach ($conversation->leadsCaptures as $lead)
                        <div class="border-t first:border-t-0 pt-2 first:pt-0">
                            <div class="font-medium">{{ $lead->name }}</div>
                            <div class="text-gray-500">{{ $lead->contact }}</div>
                            @if ($lead->formation_interest)
                                <div class="text-gray-500 mt-1">{{ $lead->formation_interest }}</div>
                            @endif
                            <a href="{{ route('admin.assistant-leads.show', $lead) }}" class="text-epa-blue hover:underline text-xs">Voir la fiche prospect &rarr;</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
