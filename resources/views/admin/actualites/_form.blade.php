@php $actualite ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="title_fr" value="Titre (FR)" />
        <x-text-input id="title_fr" name="title_fr" type="text" class="mt-1 block w-full"
                       value="{{ old('title_fr', $actualite?->title_fr) }}" required autofocus />
    </div>
    <div>
        <x-input-label for="title_en" value="Titre (EN)" />
        <x-text-input id="title_en" name="title_en" type="text" class="mt-1 block w-full"
                       value="{{ old('title_en', $actualite?->title_en) }}" required />
    </div>

    <div>
        <x-input-label for="slug" value="Slug (URL, laisser vide pour auto)" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full"
                       value="{{ old('slug', $actualite?->slug) }}" />
    </div>

    <div>
        <x-input-label for="image" value="Image" />
        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        @if ($actualite?->image)
            <img src="{{ asset('storage/'.$actualite->image) }}" alt="" class="mt-2 h-24 rounded object-cover">
        @endif
    </div>

    <div>
        <x-input-label for="excerpt_fr" value="Extrait (FR)" />
        <textarea id="excerpt_fr" name="excerpt_fr" rows="2"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('excerpt_fr', $actualite?->excerpt_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="excerpt_en" value="Extrait (EN)" />
        <textarea id="excerpt_en" name="excerpt_en" rows="2"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('excerpt_en', $actualite?->excerpt_en) }}</textarea>
    </div>

    <div>
        <x-input-label for="content_fr" value="Contenu (FR)" />
        <textarea id="content_fr" name="content_fr" rows="8"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('content_fr', $actualite?->content_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="content_en" value="Contenu (EN)" />
        <textarea id="content_en" name="content_en" rows="8"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('content_en', $actualite?->content_en) }}</textarea>
    </div>

    <div class="flex items-center gap-2">
        <input id="published" name="published" type="checkbox" value="1"
               class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               {{ old('published', filled($actualite?->published_at)) ? 'checked' : '' }}>
        <x-input-label for="published" value="Publier sur le site public" />
    </div>
</div>
