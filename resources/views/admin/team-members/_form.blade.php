@php $teamMember ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" value="Nom complet" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                       value="{{ old('name', $teamMember?->name) }}" required autofocus />
    </div>

    <div>
        <x-input-label for="photo" value="Photo" />
        <input id="photo" name="photo" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        @if ($teamMember?->photo)
            <img src="{{ asset('storage/'.$teamMember->photo) }}" alt="" class="mt-2 h-20 w-20 rounded-full object-cover">
        @endif
    </div>

    <div>
        <x-input-label for="role_fr" value="Fonction / spécialité (FR)" />
        <x-text-input id="role_fr" name="role_fr" type="text" class="mt-1 block w-full"
                       value="{{ old('role_fr', $teamMember?->role_fr) }}" />
    </div>
    <div>
        <x-input-label for="role_en" value="Fonction / spécialité (EN)" />
        <x-text-input id="role_en" name="role_en" type="text" class="mt-1 block w-full"
                       value="{{ old('role_en', $teamMember?->role_en) }}" />
    </div>

    <div>
        <x-input-label for="bio_fr" value="Bio (FR)" />
        <textarea id="bio_fr" name="bio_fr" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('bio_fr', $teamMember?->bio_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="bio_en" value="Bio (EN)" />
        <textarea id="bio_en" name="bio_en" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('bio_en', $teamMember?->bio_en) }}</textarea>
    </div>

    <div>
        <x-input-label for="order" value="Ordre d'affichage" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full"
                       value="{{ old('order', $teamMember?->order ?? 0) }}" />
    </div>

    <div class="flex items-center gap-2 self-end">
        <input id="active" name="active" type="checkbox" value="1"
               class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               {{ old('active', $teamMember?->active ?? true) ? 'checked' : '' }}>
        <x-input-label for="active" value="Afficher sur le site" />
    </div>
</div>
