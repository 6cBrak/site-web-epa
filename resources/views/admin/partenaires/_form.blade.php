@php $partenaire ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" value="Nom" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                       value="{{ old('name', $partenaire?->name) }}" required autofocus />
    </div>

    <div>
        <x-input-label for="category" value="Catégorie" />
        <select id="category" name="category" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
            @foreach (['etat' => 'État', 'ong' => 'ONG', 'association' => 'Association locale', 'entreprise' => 'Entreprise privée', 'ambassade' => 'Ambassade'] as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $partenaire?->category) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="website" value="Site web (optionnel)" />
        <x-text-input id="website" name="website" type="url" class="mt-1 block w-full"
                       value="{{ old('website', $partenaire?->website) }}" />
    </div>

    <div>
        <x-input-label for="logo" value="Logo" />
        <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        @if ($partenaire?->logo)
            <img src="{{ asset('storage/'.$partenaire->logo) }}" alt="" class="mt-2 h-16 rounded object-contain">
        @endif
    </div>

    <div class="flex items-center gap-2">
        <input id="active" name="active" type="checkbox" value="1"
               class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               {{ old('active', $partenaire?->active ?? true) ? 'checked' : '' }}>
        <x-input-label for="active" value="Afficher sur le site" />
    </div>
</div>
