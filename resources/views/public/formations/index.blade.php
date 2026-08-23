<x-public-layout title="Nos formations" description="Découvrez toutes les formations professionnelles EPA_BURKINA en Informatique &amp; Digitalisation et Action Humanitaire &amp; Développement, disponibles à Ouagadougou, Bobo-Dioulasso et Dori.">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold mb-2 text-center">Nos formations</h1>
        <p class="text-gray-500 text-center mb-10">{{ $formations->count() }} formation(s) disponible(s)</p>

        {{-- Filtres --}}
        <div class="flex flex-wrap gap-3 justify-center mb-12">
            <a href="{{ route('formations.index') }}"
               class="px-4 py-1.5 rounded-full text-sm border {{ ! $selectedProgramme && ! $selectedAntenne ? 'bg-epa-red text-white border-epa-red' : 'border-gray-300 text-gray-600' }}">
                Tout
            </a>
            @foreach ($programmes as $programme)
                <a href="{{ route('formations.index', ['programme' => $programme->slug, 'antenne' => $selectedAntenne]) }}"
                   class="px-4 py-1.5 rounded-full text-sm border {{ $selectedProgramme === $programme->slug ? 'bg-epa-red text-white border-epa-red' : 'border-gray-300 text-gray-600' }}">
                    {{ $programme->name }}
                </a>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-3 justify-center mb-12">
            <a href="{{ route('formations.index', ['programme' => $selectedProgramme]) }}"
               class="px-3 py-1 rounded-full text-xs border {{ ! $selectedAntenne ? 'bg-epa-black text-white border-epa-black' : 'border-gray-300 text-gray-500' }}">
                Toutes les antennes
            </a>
            @foreach ($antennes as $antenne)
                <a href="{{ route('formations.index', ['programme' => $selectedProgramme, 'antenne' => $antenne->slug]) }}"
                   class="px-3 py-1 rounded-full text-xs border {{ $selectedAntenne === $antenne->slug ? 'bg-epa-black text-white border-epa-black' : 'border-gray-300 text-gray-500' }}">
                    {{ $antenne->name }}
                </a>
            @endforeach
        </div>

        {{-- Grille --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($formations as $formation)
                <a href="{{ route('formations.show', $formation) }}"
                   class="group block rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition overflow-hidden">
                    <div class="h-36 bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if ($formation->image)
                            <img src="{{ asset('storage/'.$formation->image) }}" alt="{{ $formation->title }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                            <span class="w-3 h-3 rounded-full" style="background-color: {{ $formation->programme->color ?? '#EE0916' }}"></span>
                        @endif
                    </div>
                    <div class="p-5">
                        <span class="text-xs font-medium text-epa-red uppercase">{{ $formation->programme->name }}</span>
                        <h3 class="font-semibold mt-1 mb-2 group-hover:text-epa-red transition">{{ $formation->title }}</h3>
                        <div class="flex items-center gap-3 text-xs text-gray-500">
                            @if ($formation->duration)
                                <span>{{ $formation->duration }}</span>
                            @endif
                            @if ($formation->price)
                                <span>{{ number_format($formation->price, 0, ',', ' ') }} FCFA</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">Aucune formation ne correspond à ces critères.</p>
            @endforelse
        </div>
    </section>
</x-public-layout>
