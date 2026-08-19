<x-public-layout title="Actualités">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold mb-2 text-center">Actualités</h1>
        <p class="text-gray-500 text-center mb-12">Annonces, événements et succès de nos apprenants</p>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($actualites as $actualite)
                <a href="{{ route('actualites.show', $actualite) }}"
                   class="group block rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition overflow-hidden">
                    <div class="h-40 bg-gray-100"
                         @if ($actualite->image) style="background-image:url('{{ asset('storage/'.$actualite->image) }}');background-size:cover;background-position:center;" @endif>
                    </div>
                    <div class="p-5">
                        <div class="text-xs text-gray-400 mb-2">{{ $actualite->published_at->translatedFormat('d F Y') }}</div>
                        <h3 class="font-semibold mb-2 group-hover:text-epa-red transition">{{ $actualite->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $actualite->excerpt }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">Aucune actualité publiée pour l'instant.</p>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $actualites->links() }}
        </div>
    </section>
</x-public-layout>
