@php use App\Models\Setting; @endphp
<x-public-layout>
    {{-- Hero --}}
    <section class="bg-epa-black text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 grid md:grid-cols-2 gap-10 md:items-stretch">
            <div class="flex flex-col justify-center">
                <p class="text-epa-red font-semibold tracking-wide uppercase text-sm mb-3">{{ Setting::text('hero_kicker') }}</p>
                <h1 class="text-4xl sm:text-5xl font-bold leading-tight mb-6">
                    {{ Setting::text('hero_title') }}
                </h1>
                <p class="text-gray-300 mb-8">
                    {{ Setting::text('hero_subtitle') }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('candidatures.create') }}"
                       class="inline-flex items-center px-6 py-3 rounded-md bg-epa-red text-white font-semibold hover:opacity-90 transition">
                        {{ Setting::text('hero_cta_primary') }}
                    </a>
                    <a href="{{ route('formations.index') }}"
                       class="inline-flex items-center px-6 py-3 rounded-md border border-white/30 text-white font-semibold hover:bg-white/10 transition">
                        {{ Setting::text('hero_cta_secondary') }}
                    </a>
                </div>
            </div>

            {{-- Diaporama --}}
            <div class="relative rounded-2xl overflow-hidden shadow-2xl h-72 sm:h-96 md:h-full md:min-h-[480px]"
                 x-data="{ slide: 0 }"
                 @if ($heroSlides->count() > 1)
                 x-init="setInterval(() => slide = (slide + 1) % {{ $heroSlides->count() }}, {{ (int) round(((float) Setting::get('hero_slide_delay_seconds', '4.5')) * 1000) }})"
                 @endif>
                @forelse ($heroSlides as $i => $item)
                    <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->caption }}"
                         class="absolute inset-0 w-full h-full object-cover"
                         @if ($i !== 0) x-cloak @endif
                         x-show="slide === {{ $i }}"
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-700"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                    @if ($item->caption)
                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 to-transparent px-5 py-4"
                             @if ($i !== 0) x-cloak @endif
                             x-show="slide === {{ $i }}">
                            <p class="text-sm font-medium text-white">{{ $item->caption }}</p>
                        </div>
                    @endif
                @empty
                    <div class="absolute inset-0 bg-white flex items-center justify-center p-10">
                        <img src="{{ Setting::logoUrl() }}" alt="EPA" class="max-w-full max-h-full object-contain">
                    </div>
                @endforelse

                @if ($heroSlides->count() > 1)
                    <div class="absolute bottom-4 right-4 flex gap-1.5">
                        @foreach ($heroSlides as $i => $item)
                            <button type="button" @click="slide = {{ $i }}"
                                    class="w-2 h-2 rounded-full transition"
                                    :class="slide === {{ $i }} ? 'bg-white' : 'bg-white/40'"
                                    aria-label="Image {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Chiffres clés --}}
    @if ($keyStats->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-gray-100 rounded-xl shadow-lg overflow-hidden">
                @foreach ($keyStats as $stat)
                    <div class="bg-white p-6 text-center">
                        <div class="text-3xl font-bold text-epa-red">{{ $stat->value }}{{ $stat->suffix }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ $stat->label }}</div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Programmes --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-2xl font-bold text-center mb-2">{{ Setting::text('home_programmes_title') }}</h2>
        <p class="text-gray-500 text-center mb-12">{{ str_replace('{count}', $programmes->sum('formations_count'), Setting::text('home_programmes_subtitle')) }}</p>

        <div class="grid md:grid-cols-2 gap-8">
            @foreach ($programmes as $programme)
                <a href="{{ route('formations.index', ['programme' => $programme->slug]) }}"
                   class="group block p-8 rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition">
                    <div class="w-10 h-10 rounded-full mb-4" style="background-color: {{ $programme->color ?? '#EE0916' }}"></div>
                    <h3 class="text-xl font-semibold mb-2 group-hover:text-epa-red transition">{{ $programme->name }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ $programme->formations_count }} formations disponibles</p>
                    <span class="text-epa-red text-sm font-medium">Découvrir →</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Avantages --}}
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ([1, 2, 3, 4] as $n)
                <div>
                    <div class="w-12 h-12 mx-auto rounded-full bg-epa-red/10 flex items-center justify-center mb-3">
                        <span class="w-3 h-3 rounded-full bg-epa-red"></span>
                    </div>
                    <p class="text-sm font-medium text-gray-700">{{ Setting::text('home_advantage_'.$n) }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Antennes --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-2xl font-bold text-center mb-12">{{ Setting::text('home_antennes_title') }}</h2>
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
                <h2 class="text-2xl font-bold text-center mb-12">{{ Setting::text('home_actualites_title') }}</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach ($actualites as $actualite)
                        <a href="{{ route('actualites.show', $actualite) }}" class="block bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                            @if ($actualite->image)
                                <img src="{{ asset('storage/'.$actualite->image) }}" alt="" class="w-full h-40 object-cover">
                            @endif
                            <div class="p-5">
                                <h3 class="font-semibold mb-2">{{ $actualite->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $actualite->excerpt }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('actualites.index') }}" class="text-epa-red text-sm font-medium hover:underline">{{ Setting::text('home_actualites_link') }}</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Partenaires --}}
    @if ($partenaires->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h2 class="text-2xl font-bold text-center mb-2">{{ Setting::text('home_partenaires_title') }}</h2>
            <p class="text-gray-500 text-center mb-10">{{ Setting::text('home_partenaires_subtitle') }}</p>
            <div class="flex flex-wrap items-center justify-center gap-10">
                @foreach ($partenaires as $partenaire)
                    @if ($partenaire->logo)
                        <img src="{{ asset('storage/'.$partenaire->logo) }}" alt="{{ $partenaire->name }}" class="h-12 object-contain grayscale hover:grayscale-0 transition">
                    @else
                        <span class="text-sm font-medium text-gray-500">{{ $partenaire->name }}</span>
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    {{-- CTA final --}}
    <section class="bg-epa-red">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center text-white">
            <h2 class="text-2xl font-bold mb-4">{{ Setting::text('home_cta_title') }}</h2>
            <p class="mb-8 text-white/90">{{ Setting::text('home_cta_subtitle') }}</p>
            <a href="{{ route('candidatures.create') }}"
               class="inline-flex items-center px-8 py-3 rounded-md bg-white text-epa-red font-semibold hover:bg-gray-100 transition">
                {{ Setting::text('home_cta_button') }}
            </a>
        </div>
    </section>
</x-public-layout>
