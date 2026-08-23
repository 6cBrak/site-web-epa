@php $programme ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name_fr" value="Nom (FR)" />
        <x-text-input id="name_fr" name="name_fr" type="text" class="mt-1 block w-full"
                       value="{{ old('name_fr', $programme?->name_fr) }}" required autofocus />
    </div>

    <div>
        <x-input-label for="name_en" value="Nom (EN)" />
        <x-text-input id="name_en" name="name_en" type="text" class="mt-1 block w-full"
                       value="{{ old('name_en', $programme?->name_en) }}" required />
    </div>

    <div>
        <x-input-label for="slug" value="Slug (URL, laisser vide pour auto)" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full"
                       value="{{ old('slug', $programme?->slug) }}" />
    </div>

    <div>
        <x-input-label for="color" value="Couleur (hex, ex: #E4572E)" />
        <x-text-input id="color" name="color" type="text" class="mt-1 block w-full"
                       value="{{ old('color', $programme?->color) }}" />
    </div>

    <div>
        <x-input-label for="icon" value="Icône (affichée sur la page d'accueil)" />
        @if ($programme?->icon)
            <img src="{{ asset('storage/'.$programme->icon) }}" alt="" class="h-10 w-10 object-contain mt-1 mb-2 rounded"
                 style="background-color: {{ $programme->color ?? '#EE0916' }}">
        @endif
        <input id="icon" name="icon" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        <p class="mt-1 text-xs text-gray-500">Idéalement une icône simple en blanc sur fond transparent (PNG/SVG). Si aucune icône n'est fournie, une icône générique s'affiche.</p>
        <x-input-error :messages="$errors->get('icon')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="description_fr" value="Description (FR)" />
        <textarea id="description_fr" name="description_fr" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('description_fr', $programme?->description_fr) }}</textarea>
    </div>

    <div>
        <x-input-label for="description_en" value="Description (EN)" />
        <textarea id="description_en" name="description_en" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('description_en', $programme?->description_en) }}</textarea>
    </div>

    <div>
        <x-input-label for="order" value="Ordre d'affichage" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full"
                       value="{{ old('order', $programme?->order ?? 0) }}" />
    </div>

    <div class="flex items-center gap-2 self-end">
        <input id="active" name="active" type="checkbox" value="1"
               class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               {{ old('active', $programme?->active ?? true) ? 'checked' : '' }}>
        <x-input-label for="active" value="Programme actif" />
    </div>
</div>
