<x-public-layout :title="$actualite->title_fr">
    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <a href="{{ route('actualites.index') }}" class="text-sm text-gray-500 hover:text-epa-red">&larr; Toutes les actualités</a>

        <div class="text-xs text-gray-400 mt-4 mb-2">{{ $actualite->published_at->translatedFormat('d F Y') }}</div>
        <h1 class="text-3xl font-bold mb-6">{{ $actualite->title_fr }}</h1>

        @if ($actualite->image)
            <img src="{{ asset('storage/'.$actualite->image) }}" alt="" class="w-full h-72 object-cover rounded-xl mb-8">
        @endif

        <div class="prose max-w-none text-gray-700 whitespace-pre-line">{{ $actualite->content_fr }}</div>
    </section>
</x-public-layout>
