@php
    use App\Models\Setting;

    $footerAntennes = \App\Models\Antenne::where('active', true)->orderBy('name')->get();
    $whatsappAntenne = $footerAntennes->first();
    $whatsappNumber = $whatsappAntenne ? preg_replace('/\D/', '', $whatsappAntenne->phone) : null;

    $metaTitle = ($title ? $title.' — ' : '').config('app.name');
    $metaDescription = $description ?: 'EPA_BURKINA — Centre de formation professionnelle en Informatique & Action Humanitaire au Burkina Faso (Ouagadougou, Bobo-Dioulasso, Dori).';
    $metaImage = $image ?: Setting::logoUrl();

    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'EducationalOrganization',
        'name' => 'EPA_BURKINA',
        'url' => url('/'),
        'logo' => Setting::logoUrl(),
        'description' => 'Centre de formation professionnelle en Informatique & Action Humanitaire au Burkina Faso.',
        'location' => $footerAntennes->map(fn ($antenne) => [
            '@type' => 'Place',
            'name' => $antenne->name,
            'address' => $antenne->address,
        ])->values()->all(),
        'contactPoint' => $whatsappAntenne ? [
            '@type' => 'ContactPoint',
            'telephone' => $whatsappAntenne->phone,
            'contactType' => 'customer service',
        ] : null,
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="yGECSEOlfcpldMxY7h_IJ2MmwIscalmDdPQbT47SkTE" />
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @if ($noindex)
        <meta name="robots" content="noindex, nofollow">
    @endif

    {{-- Open Graph / réseaux sociaux (WhatsApp, Facebook...) --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">{!! json_encode(array_filter($organizationSchema), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @if ($schema)
        <script type="application/ld+json">{!! $schema !!}</script>
    @endif
</head>
<body class="font-sans antialiased bg-white text-epa-black">

    {{-- Header --}}
    <header x-data="{ mobileOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ Setting::logoUrl() }}" alt="EPA" class="h-12 w-auto max-w-[9rem] object-contain">
                <span class="hidden sm:block text-sm text-gray-500 leading-tight">
                    {{ Setting::text('header_tagline_line1') }}<br>
                    <span class="font-semibold text-epa-black">{{ Setting::text('header_tagline_line2') }}</span>
                </span>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="hover:text-epa-red {{ request()->routeIs('home') ? 'text-epa-red' : 'text-gray-700' }}">{{ Setting::text('nav_home') }}</a>
                <a href="{{ route('about') }}" class="hover:text-epa-red {{ request()->routeIs('about') ? 'text-epa-red' : 'text-gray-700' }}">{{ Setting::text('nav_about') }}</a>
                <a href="{{ route('formations.index') }}" class="hover:text-epa-red {{ request()->routeIs('formations.*') ? 'text-epa-red' : 'text-gray-700' }}">{{ Setting::text('nav_formations') }}</a>
                <a href="{{ route('actualites.index') }}" class="hover:text-epa-red {{ request()->routeIs('actualites.*') ? 'text-epa-red' : 'text-gray-700' }}">{{ Setting::text('nav_actualites') }}</a>
                <a href="{{ route('contact') }}" class="hover:text-epa-red {{ request()->routeIs('contact') ? 'text-epa-red' : 'text-gray-700' }}">{{ Setting::text('nav_contact') }}</a>
            </nav>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-1 text-xs font-semibold text-gray-400">
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'fr']) }}" class="{{ app()->getLocale() === 'fr' ? 'text-epa-red' : 'hover:text-epa-black' }}">FR</a>
                    <span>/</span>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" class="{{ app()->getLocale() === 'en' ? 'text-epa-red' : 'hover:text-epa-black' }}">EN</a>
                </div>

                <a href="{{ route('formations.index') }}"
                   class="hidden sm:inline-flex items-center px-5 py-2.5 rounded-md bg-epa-red text-white text-sm font-semibold hover:opacity-90 transition">
                    {{ Setting::text('nav_cta') }}
                </a>

                <button type="button" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen"
                        class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-md text-gray-700 hover:bg-gray-100"
                        aria-label="Ouvrir le menu">
                    <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                    </svg>
                    <svg x-show="mobileOpen" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile nav --}}
        <nav x-show="mobileOpen" x-cloak x-transition.opacity.duration.150ms @click.outside="mobileOpen = false"
             class="md:hidden flex flex-col gap-1 text-sm font-medium border-t border-gray-100 px-4 py-3 bg-white">
            <a href="{{ route('home') }}" @click="mobileOpen = false" class="px-3 py-2.5 rounded-md {{ request()->routeIs('home') ? 'text-epa-red bg-epa-red/5' : 'text-gray-700' }}">{{ Setting::text('nav_home') }}</a>
            <a href="{{ route('about') }}" @click="mobileOpen = false" class="px-3 py-2.5 rounded-md {{ request()->routeIs('about') ? 'text-epa-red bg-epa-red/5' : 'text-gray-700' }}">{{ Setting::text('nav_about') }}</a>
            <a href="{{ route('formations.index') }}" @click="mobileOpen = false" class="px-3 py-2.5 rounded-md {{ request()->routeIs('formations.*') ? 'text-epa-red bg-epa-red/5' : 'text-gray-700' }}">{{ Setting::text('nav_formations') }}</a>
            <a href="{{ route('actualites.index') }}" @click="mobileOpen = false" class="px-3 py-2.5 rounded-md {{ request()->routeIs('actualites.*') ? 'text-epa-red bg-epa-red/5' : 'text-gray-700' }}">{{ Setting::text('nav_actualites') }}</a>
            <a href="{{ route('contact') }}" @click="mobileOpen = false" class="px-3 py-2.5 rounded-md {{ request()->routeIs('contact') ? 'text-epa-red bg-epa-red/5' : 'text-gray-700' }}">{{ Setting::text('nav_contact') }}</a>

            <div class="flex items-center gap-3 px-3 pt-2 text-xs font-semibold text-gray-400">
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'fr']) }}" class="{{ app()->getLocale() === 'fr' ? 'text-epa-red' : '' }}">FR</a>
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" class="{{ app()->getLocale() === 'en' ? 'text-epa-red' : '' }}">EN</a>
            </div>

            <a href="{{ route('candidatures.create') }}" @click="mobileOpen = false"
               class="mt-2 text-center px-3 py-2.5 rounded-md bg-epa-red text-white font-semibold sm:hidden">
                {{ Setting::text('nav_cta') }}
            </a>
        </nav>
    </header>

    {{-- Flash messages --}}
    @if (session('status'))
        <div class="max-w-4xl mx-auto mt-4 px-4">
            <div class="rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-epa-black text-gray-300 mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-1">
                <div class="inline-flex bg-white rounded-md p-2 mb-3">
                    <img src="{{ Setting::logoUrl() }}" alt="EPA" class="h-10 w-auto max-w-[8rem] object-contain">
                </div>
                <p class="text-sm text-gray-400">{{ Setting::text('footer_tagline') }}</p>
            </div>

            @foreach ($footerAntennes as $antenne)
                <div>
                    <h3 class="text-white font-semibold text-sm mb-2">{{ $antenne->name }}</h3>
                    <p class="text-sm text-gray-400">{{ $antenne->address }}</p>
                    @if ($antenne->phone)
                        <p class="text-sm text-gray-400 mt-1">{{ $antenne->phone }}</p>
                    @endif
                    @if ($antenne->email)
                        <p class="text-sm text-gray-400">{{ $antenne->email }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="border-t border-white/10 py-4 text-center text-xs text-gray-500">
            &copy; {{ now()->year }} EPA_BURKINA — {{ Setting::text('footer_rights') }}.
        </div>
    </footer>

    {{-- Bouton WhatsApp flottant --}}
    @if ($whatsappNumber)
        <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener"
           class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 rounded-full bg-green-500 text-white shadow-lg hover:bg-green-600 transition"
           aria-label="Contacter EPA sur WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.39 1.26 4.81L2 22l5.42-1.36a9.9 9.9 0 0 0 4.62 1.14h.01c5.46 0 9.9-4.45 9.9-9.91S17.5 2 12.04 2Zm5.79 14.06c-.24.68-1.4 1.3-1.94 1.35-.5.05-1.02.24-3.4-.71-2.88-1.15-4.72-4.06-4.86-4.25-.14-.19-1.16-1.54-1.16-2.94s.73-2.09.99-2.38c.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.81 1.99.88 2.13.07.14.12.31.02.5-.1.19-.15.31-.29.48-.14.17-.31.38-.44.51-.15.15-.3.31-.13.6.17.29.76 1.25 1.62 2.03 1.12 1 2.07 1.31 2.36 1.46.29.15.46.13.63-.08.17-.21.72-.84.91-1.13.19-.29.38-.24.63-.14.26.1 1.65.78 1.93.92.29.14.48.21.55.33.07.12.07.7-.17 1.38Z"/>
            </svg>
        </a>
    @endif

    {{-- Assistant IA --}}
    @include('partials.assistant-widget')

</body>
</html>
