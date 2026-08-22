<x-admin-layout title="Prospect">
    <a href="{{ route('admin.assistant-leads.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Retour à la liste</a>

    <div class="mt-4 grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <h2 class="font-semibold text-lg">{{ $lead->name }}</h2>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Contact</span><br>{{ $lead->contact }}</div>
                    <div><span class="text-gray-500">Formation d'intérêt</span><br>{{ $lead->formation_interest ?: '—' }}</div>
                </div>

                @if ($lead->notes)
                    <div>
                        <span class="text-gray-500 text-sm">Notes de qualification</span>
                        <p class="text-sm mt-1">{{ $lead->notes }}</p>
                    </div>
                @endif

                <div class="text-xs text-gray-400 pt-2 border-t">
                    Capté le {{ $lead->captured_at->format('d/m/Y à H:i') }}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">Conversation</h3>
                <div class="space-y-3 max-h-[500px] overflow-y-auto">
                    @forelse ($lead->conversation->messages as $message)
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
        </div>

        <div class="bg-white rounded-lg shadow p-6 space-y-4 h-fit">
            <h3 class="font-semibold">Statut</h3>
            <form method="POST" action="{{ route('admin.assistant-leads.update', $lead) }}" class="space-y-3">
                @csrf
                @method('PUT')
                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                    @foreach (['nouveau' => 'Nouveau', 'contacte' => 'Contacté', 'converti' => 'Converti', 'perdu' => 'Perdu'] as $value => $label)
                        <option value="{{ $value }}" @selected($lead->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
                    Mettre à jour
                </button>
            </form>

            <form method="POST" action="{{ route('admin.assistant-leads.destroy', $lead) }}"
                  onsubmit="return confirm('Supprimer définitivement ce prospect ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 border border-red-200 text-epa-red text-sm font-medium rounded-md hover:bg-red-50">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
