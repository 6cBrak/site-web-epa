@php $antenne ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" value="Nom" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                       value="{{ old('name', $antenne?->name) }}" required autofocus />
    </div>

    <div>
        <x-input-label for="slug" value="Slug (URL, laisser vide pour auto)" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full"
                       value="{{ old('slug', $antenne?->slug) }}" />
    </div>

    <div>
        <x-input-label for="phone" value="Téléphone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                       value="{{ old('phone', $antenne?->phone) }}" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                       value="{{ old('email', $antenne?->email) }}" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="address" value="Adresse" />
        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                       value="{{ old('address', $antenne?->address) }}" />
    </div>

    <div>
        <x-input-label for="description_fr" value="Description (FR)" />
        <textarea id="description_fr" name="description_fr" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('description_fr', $antenne?->description_fr) }}</textarea>
    </div>

    <div>
        <x-input-label for="description_en" value="Description (EN)" />
        <textarea id="description_en" name="description_en" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('description_en', $antenne?->description_en) }}</textarea>
    </div>

    <div class="flex items-center gap-2">
        <input id="active" name="active" type="checkbox" value="1"
               class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               {{ old('active', $antenne?->active ?? true) ? 'checked' : '' }}>
        <x-input-label for="active" value="Antenne active" />
    </div>
</div>
