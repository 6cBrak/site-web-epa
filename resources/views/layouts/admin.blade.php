<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Back-office' }} — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="w-64 shrink-0 bg-epa-black text-white flex flex-col">
            <div class="h-16 flex items-center gap-2 px-5 border-b border-white/10">
                <img src="{{ asset('images/logo.jpeg') }}" alt="EPA" class="h-8 w-8 rounded object-cover">
                <span class="font-semibold tracking-wide">EPA Admin</span>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                @php
                    $links = [
                        ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Tableau de bord'],
                        ['route' => 'admin.hero-slides.index', 'match' => 'admin.hero-slides.*', 'label' => 'Diaporama accueil'],
                        ['route' => 'admin.antennes.index', 'match' => 'admin.antennes.*', 'label' => 'Antennes'],
                        ['route' => 'admin.programmes.index', 'match' => 'admin.programmes.*', 'label' => 'Programmes'],
                        ['route' => 'admin.formations.index', 'match' => 'admin.formations.*', 'label' => 'Formations'],
                        ['route' => 'admin.formation-sessions.index', 'match' => 'admin.formation-sessions.*', 'label' => 'Sessions'],
                        ['route' => 'admin.candidatures.index', 'match' => 'admin.candidatures.*', 'label' => 'Candidatures'],
                        ['route' => 'admin.actualites.index', 'match' => 'admin.actualites.*', 'label' => 'Actualités'],
                        ['route' => 'admin.team-members.index', 'match' => 'admin.team-members.*', 'label' => 'Équipe'],
                        ['route' => 'admin.partenaires.index', 'match' => 'admin.partenaires.*', 'label' => 'Partenaires'],
                        ['route' => 'admin.settings.edit', 'match' => 'admin.settings.*', 'label' => 'Textes du site'],
                    ];
                @endphp

                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="block px-3 py-2 rounded-md text-sm font-medium transition
                              {{ request()->routeIs($link['match'])
                                    ? 'bg-epa-red text-white'
                                    : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 border-t border-white/10 text-xs text-gray-400">
                Connecté : {{ Auth::user()->name }}
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="h-16 bg-white border-b flex items-center justify-between px-6">
                <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Tableau de bord' }}</h1>

                <div class="flex items-center gap-4">
                    <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-epa-red">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-epa-red">Déconnexion</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-6">
                @if (session('status'))
                    <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
