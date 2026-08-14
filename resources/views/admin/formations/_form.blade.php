@php
    $formation ??= null;
    $selectedAntenneIds ??= [];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="programme_id" value="Programme" />
        <select id="programme_id" name="programme_id" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
            <option value="">— Choisir —</option>
            @foreach ($programmes as $programme)
                <option value="{{ $programme->id }}" @selected(old('programme_id', $formation?->programme_id) == $programme->id)>
                    {{ $programme->name_fr }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="slug" value="Slug (URL, laisser vide pour auto)" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full"
                       value="{{ old('slug', $formation?->slug) }}" />
    </div>

    <div>
        <x-input-label for="title_fr" value="Titre (FR)" />
        <x-text-input id="title_fr" name="title_fr" type="text" class="mt-1 block w-full"
                       value="{{ old('title_fr', $formation?->title_fr) }}" required />
    </div>

    <div>
        <x-input-label for="title_en" value="Titre (EN)" />
        <x-text-input id="title_en" name="title_en" type="text" class="mt-1 block w-full"
                       value="{{ old('title_en', $formation?->title_en) }}" required />
    </div>

    <div>
        <x-input-label for="duration" value="Durée (ex: 3 mois)" />
        <x-text-input id="duration" name="duration" type="text" class="mt-1 block w-full"
                       value="{{ old('duration', $formation?->duration) }}" />
    </div>

    <div>
        <x-input-label for="price" value="Prix (FCFA)" />
        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                       value="{{ old('price', $formation?->price) }}" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="image" value="Image" />
        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        @if ($formation?->image)
            <img src="{{ asset('storage/'.$formation->image) }}" alt="" class="mt-2 h-24 rounded object-cover">
        @endif
    </div>

    <div>
        <x-input-label for="description_fr" value="Description (FR)" />
        <textarea id="description_fr" name="description_fr" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('description_fr', $formation?->description_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="description_en" value="Description (EN)" />
        <textarea id="description_en" name="description_en" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('description_en', $formation?->description_en) }}</textarea>
    </div>

    <div>
        <x-input-label for="objectives_fr" value="Objectifs pédagogiques (FR)" />
        <textarea id="objectives_fr" name="objectives_fr" rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('objectives_fr', $formation?->objectives_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="objectives_en" value="Objectifs pédagogiques (EN)" />
        <textarea id="objectives_en" name="objectives_en" rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('objectives_en', $formation?->objectives_en) }}</textarea>
    </div>

    <div>
        <x-input-label for="modules_fr" value="Programme détaillé / modules (FR)" />
        <textarea id="modules_fr" name="modules_fr" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('modules_fr', $formation?->modules_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="modules_en" value="Programme détaillé / modules (EN)" />
        <textarea id="modules_en" name="modules_en" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('modules_en', $formation?->modules_en) }}</textarea>
    </div>

    <div>
        <x-input-label for="prerequisites_fr" value="Prérequis (FR)" />
        <textarea id="prerequisites_fr" name="prerequisites_fr" rows="2"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('prerequisites_fr', $formation?->prerequisites_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="prerequisites_en" value="Prérequis (EN)" />
        <textarea id="prerequisites_en" name="prerequisites_en" rows="2"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('prerequisites_en', $formation?->prerequisites_en) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <x-input-label value="Antennes proposant cette formation" />
        <div class="mt-2 flex flex-wrap gap-4">
            @foreach ($antennes as $antenne)
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="antenne_ids[]" value="{{ $antenne->id }}"
                           class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
                           {{ in_array($antenne->id, old('antenne_ids', $selectedAntenneIds)) ? 'checked' : '' }}>
                    {{ $antenne->name }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <x-input-label for="order" value="Ordre d'affichage" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full"
                       value="{{ old('order', $formation?->order ?? 0) }}" />
    </div>

    <div class="flex items-center gap-2 self-end">
        <input id="published" name="published" type="checkbox" value="1"
               class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               {{ old('published', $formation?->published ?? false) ? 'checked' : '' }}>
        <x-input-label for="published" value="Publier sur le site public" />
    </div>
</div>
