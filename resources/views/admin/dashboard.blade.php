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

    <div class="mt-8 bg-white rounded-lg shadow p-5">
        <h2 class="font-semibold text-gray-800 mb-2">Bienvenue sur le back-office EPA</h2>
        <p class="text-sm text-gray-600">
            Utilisez le menu à gauche pour gérer les antennes, les programmes, les formations et leurs sessions.
            La gestion des candidatures, actualités, équipe, partenaires, certificats et codes promo sera ajoutée dans une prochaine étape.
        </p>
    </div>
</x-admin-layout>
