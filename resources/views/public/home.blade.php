<x-public-layout>
    {{-- Hero --}}
    <section class="bg-epa-black text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 grid md:grid-cols-2 gap-10 items-center">
            <div>
                <p class="text-epa-red font-semibold tracking-wide uppercase text-sm mb-3">Former les acteurs du développement</p>
                <h1 class="text-4xl sm:text-5xl font-bold leading-tight mb-6">
                    Des formations professionnelles en <span class="text-epa-red">Informatique</span> &amp;
                    <span class="text-epa-orange">Action Humanitaire</span>
                </h1>
                <p class="text-gray-300 mb-8">
                    EPA_BURKINA forme les jeunes professionnels d'Afrique, du niveau BEPC au BAC+,
                    pour booster leur employabilité dans les ONG et les entreprises.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('candidatures.create') }}"
                       class="inline-flex items-center px-6 py-3 rounded-md bg-epa-red text-white font-semibold hover:opacity-90 transition">
                        S'inscrire à une formation
                    </a>
                    <a href="{{ route('formations.index') }}"
                       class="inline-flex items-center px-6 py-3 rounded-md border border-white/30 text-white font-semibold hover:bg-white/10 transition">
                        Voir les formations
                    </a>
                </div>
            </div>
            <div class="flex justify-center">
                <img src="{{ asset('images/logo.jpeg') }}" alt="EPA" class="w-64 h-64 object-cover rounded-2xl shadow-2xl">
            </div>
        </div>
    </section>

    {{-- Chiffres clés --}}
    @if ($keyStats->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
            <div class="bg-white rounded-xl shadow-lg grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
                @foreach ($keyStats as $stat)
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-epa-red">{{ $stat->value }}{{ $stat->suffix }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ $stat->label_fr }}</div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Programmes --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-2xl font-bold text-center mb-2">Nos programmes de formation</h2>
        <p class="text-gray-500 text-center mb-12">Deux pôles d'excellence, {{ $programmes->sum('formations_count') }} formations disponibles</p>

        <div class="grid md:grid-cols-2 gap-8">
            @foreach ($programmes as $programme)
                <a href="{{ route('formations.index', ['programme' => $programme->slug]) }}"
                   class="group block p-8 rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition">
                    <div class="w-10 h-10 rounded-full mb-4" style="background-color: {{ $programme->color ?? '#EE0916' }}"></div>
                    <h3 class="text-xl font-semibold mb-2 group-hover:text-epa-red transition">{{ $programme->name_fr }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ $programme->formations_count }} formations disponibles</p>
                    <span class="text-epa-red text-sm font-medium">Découvrir →</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Avantages --}}
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ([
                'Formations innovantes',
                'Certification prestigieuse',
                'Accompagnement permanent',
                'Réseau puissant de partenaires',
            ] as $avantage)
                <div>
                    <div class="w-12 h-12 mx-auto rounded-full bg-epa-red/10 flex items-center justify-center mb-3">
                        <span class="w-3 h-3 rounded-full bg-epa-red"></span>
                    </div>
                    <p class="text-sm font-medium text-gray-700">{{ $avantage }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Antennes --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-2xl font-bold text-center mb-12">Nos antennes</h2>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach ($antennes as $antenne)
                <div class="p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="font-semibold mb-1">{{ $antenne->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $antenne->address }}</p>
                    @if ($antenne->phone)
                        <p class="text-sm text-gray-500 mt-2">{{ $antenne->phone }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- Actualités --}}
    @if ($actualites->isNotEmpty())
        <section class="bg-gray-50 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-center mb-12">Actualités</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach ($actualites as $actualite)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            @if ($actualite->image)
                                <img src="{{ asset('storage/'.$actualite->image) }}" alt="" class="w-full h-40 object-cover">
                            @endif
                            <div class="p-5">
                                <h3 class="font-semibold mb-2">{{ $actualite->title_fr }}</h3>
                                <p class="text-sm text-gray-500">{{ $actualite->excerpt_fr }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA final --}}
    <section class="bg-epa-red">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center text-white">
            <h2 class="text-2xl font-bold mb-4">Prêt à booster votre employabilité ?</h2>
            <p class="mb-8 text-white/90">Rejoignez EPA dès aujourd'hui, du niveau BEPC au BAC+.</p>
            <a href="{{ route('candidatures.create') }}"
               class="inline-flex items-center px-8 py-3 rounded-md bg-white text-epa-red font-semibold hover:bg-gray-100 transition">
                S'inscrire maintenant
            </a>
        </div>
    </section>
</x-public-layout>
