<x-admin-layout title="Candidature">
    <a href="{{ route('admin.candidatures.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Retour à la liste</a>

    <div class="mt-4 grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-lg shadow p-6 space-y-4">
            <div class="flex items-center gap-2">
                <h2 class="font-semibold text-lg">{{ $candidature->first_name }} {{ $candidature->last_name }}</h2>
                @if ($candidature->assistantLeadCapture)
                    <a href="{{ route('admin.assistant-leads.show', $candidature->assistantLeadCapture) }}"
                       class="inline-flex px-2 py-0.5 rounded-full text-xs bg-epa-red/10 text-epa-red hover:bg-epa-red/20">
                        💬 Provient du chat
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Email</span><br>{{ $candidature->email }}</div>
                <div><span class="text-gray-500">Téléphone</span><br>{{ $candidature->phone }}</div>
                <div><span class="text-gray-500">Niveau d'étude</span><br>{{ $candidature->education_level ?: '—' }}</div>
                <div><span class="text-gray-500">Nationalité</span><br>{{ $candidature->nationality ?: '—' }}</div>
                <div><span class="text-gray-500">Ville/Pays</span><br>{{ $candidature->city_country ?: '—' }}</div>
                <div><span class="text-gray-500">Profil</span><br>{{ ucfirst($candidature->profile_type) }}</div>
                <div><span class="text-gray-500">Formation</span><br>{{ $candidature->formation->title_fr }}</div>
                <div><span class="text-gray-500">Antenne</span><br>{{ $candidature->antenne->name }}</div>
                <div><span class="text-gray-500">Session</span><br>{{ $candidature->formationSession?->start_date?->format('d/m/Y') ?: '—' }}</div>
                <div><span class="text-gray-500">Début souhaité</span><br>{{ str_replace('_', ' ', $candidature->start_preference) ?: '—' }}</div>
                <div><span class="text-gray-500">Connu via</span><br>{{ $candidature->how_heard ?: '—' }}</div>
                <div><span class="text-gray-500">Code promo</span><br>{{ $candidature->promoCode?->code ?: '—' }}</div>
            </div>

            @if ($candidature->comment)
                <div>
                    <span class="text-gray-500 text-sm">Commentaire</span>
                    <p class="text-sm mt-1">{{ $candidature->comment }}</p>
                </div>
            @endif

            @if ($candidature->cv_path)
                <div>
                    <a href="{{ asset('storage/'.$candidature->cv_path) }}" target="_blank"
                       class="inline-flex items-center text-sm text-epa-blue hover:underline">
                        📎 Télécharger le CV
                    </a>
                </div>
            @endif

            <div class="text-xs text-gray-400 pt-2 border-t">
                Reçue le {{ $candidature->created_at->format('d/m/Y à H:i') }} · Token de suivi : {{ $candidature->tracking_token }}
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="font-semibold">Statut</h3>
            <form method="POST" action="{{ route('admin.candidatures.update', $candidature) }}" class="space-y-3">
                @csrf
                @method('PUT')
                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                    @foreach (['nouvelle' => 'Nouvelle', 'contactee' => 'Contactée', 'confirmee' => 'Confirmée', 'refusee' => 'Refusée'] as $value => $label)
                        <option value="{{ $value }}" @selected($candidature->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
                    Mettre à jour
                </button>
            </form>

            <form method="POST" action="{{ route('admin.candidatures.destroy', $candidature) }}"
                  onsubmit="return confirm('Supprimer définitivement cette candidature ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 border border-red-200 text-epa-red text-sm font-medium rounded-md hover:bg-red-50">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
