@php $heroSlide ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <x-input-label for="image" value="Image" />
        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        <p class="mt-1 text-xs text-gray-500">Format portrait ou carré recommandé (elle remplit toute la hauteur du bandeau d'accueil).</p>
        @if ($heroSlide?->image)
            <img src="{{ asset('storage/'.$heroSlide->image) }}" alt="" class="mt-2 h-40 rounded-lg object-cover">
        @endif
        <x-input-error :messages="$errors->get('image')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="caption_fr" value="Légende FR (optionnel)" />
        <x-text-input id="caption_fr" name="caption_fr" type="text" class="mt-1 block w-full"
                       value="{{ old('caption_fr', $heroSlide?->caption_fr) }}" />
    </div>

    <div>
        <x-input-label for="caption_en" value="Légende EN (optionnel)" />
        <x-text-input id="caption_en" name="caption_en" type="text" class="mt-1 block w-full"
                       value="{{ old('caption_en', $heroSlide?->caption_en) }}" />
    </div>

    <div>
        <x-input-label for="order" value="Ordre d'affichage" />
        <x-text-input id="order" name="order" type="number" min="0" class="mt-1 block w-full"
                       value="{{ old('order', $heroSlide?->order ?? 0) }}" />
    </div>

    <div class="flex items-center gap-2">
        <input id="active" name="active" type="checkbox" value="1"
               class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               {{ old('active', $heroSlide?->active ?? true) ? 'checked' : '' }}>
        <x-input-label for="active" value="Afficher sur le site" />
    </div>
</div>
