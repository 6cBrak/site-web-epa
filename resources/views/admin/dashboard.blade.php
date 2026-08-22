<x-admin-layout title="Tableau de bord">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-sm text-gray-500">Antennes</div>
            <div class="text-3xl font-semibold text-epa-black">{{ $antennesCount }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-sm text-gray-500">Programmes</div>
            <div class="text-3xl font-semibold text-epa-black">{{ $programmesCount }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-sm text-gray-500">Formations</div>
            <div class="text-3xl font-semibold text-epa-black">{{ $formationsCount }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-sm text-gray-500">Candidatures (dont nouvelles)</div>
            <div class="text-3xl font-semibold text-epa-black">
                {{ $candidaturesCount }}
                <span class="text-sm font-normal text-epa-red">({{ $candidaturesNouvelles }} nouvelles)</span>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="font-semibold text-gray-800 mb-3">Assistant IA (chat)</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-5">
                <div class="text-sm text-gray-500">Prospects captés</div>
                <div class="text-3xl font-semibold text-epa-black">
                    {{ $chatLeadsCount }}
                    <span class="text-sm font-normal text-epa-red">({{ $chatLeadsThisWeek }} cette semaine)</span>
                </div>
                @if ($chatHotLeadsPending > 0)
                    <a href="{{ route('admin.assistant-leads.index', ['priority' => 'chaud']) }}" class="text-xs text-red-600 font-medium hover:underline">
                        🔥 {{ $chatHotLeadsPending }} en attente de contact
                    </a>
                @endif
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <div class="text-sm text-gray-500">Conversations totales</div>
                <div class="text-3xl font-semibold text-epa-black">{{ $chatConversationsCount }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <div class="text-sm text-gray-500">Taux de conversion (lead → inscrit)</div>
                <div class="text-3xl font-semibold text-epa-black">
                    {{ $chatConversionRate !== null ? $chatConversionRate.'%' : '—' }}
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <div class="text-sm text-gray-500 mb-1">Formations les plus demandées</div>
                @forelse ($chatTopFormations as $row)
                    <div class="text-xs text-gray-700 truncate">{{ $row->formation_interest }} <span class="text-gray-400">({{ $row->total }})</span></div>
                @empty
                    <div class="text-xs text-gray-400">—</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow p-5">
        <h2 class="font-semibold text-gray-800 mb-2">Bienvenue sur le back-office EPA</h2>
        <p class="text-sm text-gray-600">
            Utilisez le menu à gauche pour gérer les antennes, les programmes, les formations et leurs sessions.
            La gestion des candidatures, actualités, équipe, partenaires, certificats et codes promo sera ajoutée dans une prochaine étape.
        </p>
    </div>
</x-admin-layout>
