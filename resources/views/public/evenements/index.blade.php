<x-public-layout title="Événements" description="Panels, journées portes ouvertes, ateliers et cérémonies d'EPA_BURKINA : découvrez nos prochains événements et inscrivez-vous en ligne.">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold mb-2 text-center">Événements</h1>
        <p class="text-gray-500 text-center mb-8">Panels, portes ouvertes, ateliers et cérémonies : rejoignez-nous</p>

        <form method="GET" class="flex flex-wrap justify-center gap-3 mb-12">
            <select name="type" onchange="this.form.submit()" class="border-gray-300 rounded-md text-sm focus:border-epa-red focus:ring-epa-red">
                <option value="">Tous les types</option>
                @foreach (\App\Models\Evenement::TYPES as $key => $label)
                    <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="antenne" onchange="this.form.submit()" class="border-gray-300 rounded-md text-sm focus:border-epa-red focus:ring-epa-red">
                <option value="">Toutes les antennes</option>
                @foreach ($antennes as $antenne)
                    <option value="{{ $antenne->id }}" @selected((string) request('antenne') === (string) $antenne->id)>{{ $antenne->name }}</option>
                @endforeach
            </select>
        </form>

        <h2 class="text-xl font-bold mb-6">À venir</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @forelse ($upcoming as $evenement)
                @include('public.evenements._card', ['evenement' => $evenement])
            @empty
                <p class="col-span-full text-gray-500">Aucun événement à venir pour l'instant. Revenez bientôt !</p>
            @endforelse
        </div>

        @if ($past->isNotEmpty())
            <h2 class="text-xl font-bold mb-6">Événements passés</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($past as $evenement)
                    @include('public.evenements._card', ['evenement' => $evenement, 'past' => true])
                @endforeach
            </div>
        @endif
    </section>
</x-public-layout>
