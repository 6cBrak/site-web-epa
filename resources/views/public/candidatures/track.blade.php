<x-public-layout title="Suivi de candidature">
    @php
        $statusLabels = [
            'nouvelle' => ['Reçue', 'bg-blue-100 text-blue-800'],
            'contactee' => ['Contactée', 'bg-yellow-100 text-yellow-800'],
            'confirmee' => ['Confirmée', 'bg-green-100 text-green-800'],
            'refusee' => ['Refusée', 'bg-red-100 text-red-800'],
        ];
        [$label, $classes] = $statusLabels[$candidature->status];
    @endphp

    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h1 class="text-2xl font-bold mb-8 text-center">Suivi de votre candidature</h1>

        <div class="p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-500">Statut</span>
                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $classes }}">{{ $label }}</span>
            </div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-500">Formation</span>
                <span class="text-sm font-medium">{{ $candidature->formation->title_fr }}</span>
            </div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-500">Antenne</span>
                <span class="text-sm font-medium">{{ $candidature->antenne->name }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Envoyée le</span>
                <span class="text-sm font-medium">{{ $candidature->created_at->translatedFormat('d F Y') }}</span>
            </div>
        </div>
    </section>
</x-public-layout>
