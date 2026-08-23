@php
    use Illuminate\Support\Str;

    $formationDescription = $formation->description
        ? Str::limit(strip_tags($formation->description), 160)
        : "Formation {$formation->title} ({$formation->programme->name}) chez EPA_BURKINA".($formation->duration ? " — durée : {$formation->duration}" : '').'.';

    $formationImage = $formation->image ? asset('storage/'.$formation->image) : null;

    $courseSchema = json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Course',
        'name' => $formation->title,
        'description' => $formationDescription,
        'provider' => [
            '@type' => 'EducationalOrganization',
            'name' => 'EPA_BURKINA',
            'sameAs' => url('/'),
        ],
        'offers' => $formation->price !== null ? [
            '@type' => 'Offer',
            'price' => (string) $formation->price,
            'priceCurrency' => 'XOF',
            'availability' => 'https://schema.org/InStock',
        ] : null,
    ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp
<x-public-layout :title="$formation->title" :description="$formationDescription" :image="$formationImage" :schema="$courseSchema">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <a href="{{ route('formations.index') }}" class="text-sm text-gray-500 hover:text-epa-red">&larr; Toutes les formations</a>

        <div class="mt-4 flex items-center justify-between gap-3 flex-wrap">
            <span class="text-xs font-medium text-epa-red uppercase">{{ $formation->programme->name }}</span>
            <x-share-buttons :title="$formation->title" />
        </div>
        <h1 class="text-3xl font-bold mt-2 mb-6">{{ $formation->title }}</h1>

        @if ($formation->image)
            <img src="{{ asset('storage/'.$formation->image) }}" alt="{{ $formation->title }}" class="w-full h-72 object-cover rounded-xl mb-10">
        @endif

        <div class="grid md:grid-cols-3 gap-10">
            <div class="md:col-span-2 space-y-8">
                @if ($formation->description)
                    <div>
                        <h2 class="font-semibold mb-2">Description</h2>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $formation->description }}</p>
                    </div>
                @endif

                @if ($formation->objectives)
                    <div>
                        <h2 class="font-semibold mb-2">Objectifs pédagogiques</h2>
                        <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $formation->objectives }}</p>
                    </div>
                @endif

                @if ($formation->modules)
                    <div>
                        <h2 class="font-semibold mb-2">Programme détaillé</h2>
                        <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $formation->modules }}</p>
                    </div>
                @endif

                @if ($formation->prerequisites)
                    <div>
                        <h2 class="font-semibold mb-2">Prérequis</h2>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $formation->prerequisites }}</p>
                    </div>
                @endif

                @if ($formation->sessions->isNotEmpty())
                    <div>
                        <h2 class="font-semibold mb-4">Prochaines sessions</h2>
                        <div class="space-y-3">
                            @foreach ($formation->sessions as $session)
                                <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1 p-4 rounded-lg border border-gray-100">
                                    <div class="min-w-0">
                                        <div class="font-medium text-sm">{{ $session->start_date->translatedFormat('d F Y') }} — {{ $session->antenne->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ str_replace('_', ' ', ucfirst($session->modality)) }}
                                            @if ($session->capacity !== null)
                                                · {{ $session->seatsRemaining() }} place(s) restante(s)
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-epa-red shrink-0" data-countdown="{{ $session->start_date->toDateString() }}">
                                        {{ $session->start_date->isFuture() ? 'Dans '.now()->diffInDays($session->start_date).' jour(s)' : 'En cours' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <div class="sticky top-24 p-6 rounded-xl border border-gray-100 shadow-sm space-y-3">
                    @if ($formation->duration)
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Durée</span><span class="font-medium">{{ $formation->duration }}</span></div>
                    @endif
                    @if ($formation->price)
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Prix</span><span class="font-medium">{{ number_format($formation->price, 0, ',', ' ') }} FCFA</span></div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Antennes</span>
                        <span class="font-medium text-right">{{ $formation->antennes->pluck('name')->implode(', ') ?: '—' }}</span>
                    </div>

                    <a href="{{ route('candidatures.create', ['formation' => $formation->slug]) }}"
                       class="mt-4 block text-center px-6 py-3 rounded-md bg-epa-red text-white font-semibold hover:opacity-90 transition">
                        S'inscrire à cette formation
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
