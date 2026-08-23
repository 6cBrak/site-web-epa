@php use App\Models\Setting; use Illuminate\Support\Str; @endphp
<x-public-layout :description="Str::limit(Setting::text('hero_subtitle'), 160)">
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
                         @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif
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
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-4 text-white overflow-hidden"
                         style="background-color: {{ $programme->color ?? '#EE0916' }}">
                        @if ($programme->icon)
                            <img src="{{ asset('storage/'.$programme->icon) }}" alt="" class="w-8 h-8 object-contain">
                        @else
                            {{-- Icône générique par défaut, tant qu'aucune icône n'est configurée pour ce programme dans l'admin --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.44 60.44 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443" />
                            </svg>
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold mb-2 group-hover:text-epa-red transition">{{ $programme->name }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ $programme->formations_count }} formations disponibles</p>
                    <span class="text-epa-red text-sm font-medium">Découvrir →</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Avantages --}}
    <section class="bg-gray-50 py-20">
        @php
            $advantageIcons = [
                1 => 'M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.4 14.4 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18',
                2 => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z',
                3 => 'M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 1.943.727 3.727 1.936 5.136-.276.98-.83 2.036-1.685 2.912a.75.75 0 0 0 .582 1.235c1.75 0 3.28-.65 4.402-1.398A9.9 9.9 0 0 0 12 20.25Z',
                4 => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
            ];
        @endphp
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ([1, 2, 3, 4] as $n)
                <div>
                    <div class="w-12 h-12 mx-auto rounded-full bg-epa-red/10 flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-6 h-6 text-epa-red">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $advantageIcons[$n] }}" />
                        </svg>
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
                                <img src="{{ asset('storage/'.$actualite->image) }}" alt="{{ $actualite->title }}" class="w-full h-40 object-cover" loading="lazy">
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
                        <img src="{{ asset('storage/'.$partenaire->logo) }}" alt="{{ $partenaire->name }}" class="h-12 object-contain grayscale hover:grayscale-0 transition" loading="lazy">
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
