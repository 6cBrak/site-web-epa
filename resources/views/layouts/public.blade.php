@php
    $footerAntennes = \App\Models\Antenne::where('active', true)->orderBy('name')->get();
    $whatsappAntenne = $footerAntennes->first();
    $whatsappNumber = $whatsappAntenne ? preg_replace('/\D/', '', $whatsappAntenne->phone) : null;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' — ' : '' }}{{ config('app.name') }}</title>
    <meta name="description" content="EPA — Centre de formation professionnelle en Informatique & Action Humanitaire au Burkina Faso.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-epa-black">

    {{-- Header --}}
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.jpeg') }}" alt="EPA" class="h-12 w-12 rounded object-cover">
                <span class="hidden sm:block text-sm text-gray-500 leading-tight">
                    Centre de formation<br>
                    <span class="font-semibold text-epa-black">Informatique &amp; Action Humanitaire</span>
                </span>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="hover:text-epa-red {{ request()->routeIs('home') ? 'text-epa-red' : 'text-gray-700' }}">Accueil</a>
                <a href="{{ route('about') }}" class="hover:text-epa-red {{ request()->routeIs('about') ? 'text-epa-red' : 'text-gray-700' }}">Qui sommes-nous</a>
                <a href="{{ route('formations.index') }}" class="hover:text-epa-red {{ request()->routeIs('formations.*') ? 'text-epa-red' : 'text-gray-700' }}">Nos formations</a>
                <a href="{{ route('contact') }}" class="hover:text-epa-red {{ request()->routeIs('contact') ? 'text-epa-red' : 'text-gray-700' }}">Contact</a>
            </nav>

            <a href="{{ route('formations.index') }}"
               class="inline-flex items-center px-5 py-2.5 rounded-md bg-epa-red text-white text-sm font-semibold hover:opacity-90 transition">
                S'inscrire
            </a>
        </div>

        {{-- Mobile nav --}}
        <nav class="md:hidden flex items-center justify-center gap-6 text-xs font-medium border-t border-gray-100 py-2">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-epa-red' : 'text-gray-600' }}">Accueil</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-epa-red' : 'text-gray-600' }}">Qui sommes-nous</a>
            <a href="{{ route('formations.index') }}" class="{{ request()->routeIs('formations.*') ? 'text-epa-red' : 'text-gray-600' }}">Formations</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-epa-red' : 'text-gray-600' }}">Contact</a>
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
                <img src="{{ asset('images/logo.jpeg') }}" alt="EPA" class="h-12 w-12 rounded object-cover mb-3">
                <p class="text-sm text-gray-400">Former les acteurs du développement.</p>
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
            &copy; {{ now()->year }} EPA_BURKINA — Tous droits réservés.
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

</body>
</html>
