@php
    use Illuminate\Support\Str;

    $actualiteDescription = $actualite->excerpt
        ? Str::limit(strip_tags($actualite->excerpt), 160)
        : Str::limit(strip_tags($actualite->content), 160);

    $actualiteImage = $actualite->image ? asset('storage/'.$actualite->image) : null;

    $articleSchema = json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $actualite->title,
        'description' => $actualiteDescription,
        'datePublished' => $actualite->published_at?->toIso8601String(),
        'image' => $actualiteImage,
        'publisher' => [
            '@type' => 'EducationalOrganization',
            'name' => 'EPA_BURKINA',
        ],
    ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp
<x-public-layout :title="$actualite->title" :description="$actualiteDescription" :image="$actualiteImage" :schema="$articleSchema">
    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <a href="{{ route('actualites.index') }}" class="text-sm text-gray-500 hover:text-epa-red">&larr; Toutes les actualités</a>

        <div class="flex items-center justify-between gap-3 flex-wrap mt-4 mb-2">
            <span class="text-xs text-gray-400">{{ $actualite->published_at->translatedFormat('d F Y') }}</span>
            <x-share-buttons :title="$actualite->title" />
        </div>
        <h1 class="text-3xl font-bold mb-6">{{ $actualite->title }}</h1>

        @if ($actualite->image)
            <img src="{{ asset('storage/'.$actualite->image) }}" alt="{{ $actualite->title }}" class="w-full h-72 object-cover rounded-xl mb-8">
        @endif

        <div class="prose max-w-none text-gray-700 whitespace-pre-line">{{ $actualite->content }}</div>

        <div class="mt-16 pt-10 border-t border-gray-200">
            <h2 class="text-xl font-bold mb-6">
                {{ $actualite->approvedComments->count() }} commentaire{{ $actualite->approvedComments->count() > 1 ? 's' : '' }}
            </h2>

            @if ($actualite->approvedComments->isNotEmpty())
                <ul class="space-y-6 mb-10">
                    @foreach ($actualite->approvedComments as $comment)
                        <li class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-gray-900">{{ $comment->author_name }}</span>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                            <p class="text-gray-700 whitespace-pre-line">{{ $comment->body }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if (session('status'))
                <div class="mb-6 p-3 rounded-md bg-green-50 text-green-800 text-sm">{{ session('status') }}</div>
            @endif

            <h3 class="text-lg font-semibold mb-4">Laisser un commentaire</h3>
            <form method="POST" action="{{ route('actualites.comments.store', $actualite) }}" class="space-y-4">
                @csrf

                {{-- honeypot anti-spam, laissé vide par les vrais visiteurs --}}
                <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="author_name" value="Nom" />
                        <x-text-input id="author_name" name="author_name" type="text" class="mt-1 w-full" value="{{ old('author_name') }}" required />
                        <x-input-error :messages="$errors->get('author_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="author_email" value="Email" />
                        <x-text-input id="author_email" name="author_email" type="email" class="mt-1 w-full" value="{{ old('author_email') }}" required />
                        <x-input-error :messages="$errors->get('author_email')" class="mt-1" />
                    </div>
                </div>
                <div>
                    <x-input-label for="body" value="Commentaire" />
                    <textarea id="body" name="body" rows="4" required
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('body') }}</textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-1" />
                </div>
                <button type="submit" class="px-5 py-2.5 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
                    Envoyer
                </button>
                <p class="text-xs text-gray-400">Votre commentaire sera visible après validation par l'équipe EPA.</p>
            </form>
        </div>
    </section>
</x-public-layout>
