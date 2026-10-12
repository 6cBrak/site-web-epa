@php
    $evenement ??= null;
    $dt = fn ($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('Y-m-d\TH:i') : '';

    $sessionRows = old('sessions') ?? ($evenement?->sessions->map(fn ($s) => [
        'id' => $s->id,
        'title_fr' => $s->title_fr,
        'title_en' => $s->title_en,
        'starts_at' => $dt($s->starts_at),
        'capacity' => $s->capacity,
    ])->all() ?? []);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="title_fr" value="Titre (FR)" />
        <x-text-input id="title_fr" name="title_fr" type="text" class="mt-1 block w-full"
                      value="{{ old('title_fr', $evenement?->title_fr) }}" required autofocus />
        <x-input-error :messages="$errors->get('title_fr')" class="mt-1" />
    </div>
    <div>
        <x-input-label for="title_en" value="Titre (EN)" />
        <x-text-input id="title_en" name="title_en" type="text" class="mt-1 block w-full"
                      value="{{ old('title_en', $evenement?->title_en) }}" required />
        <x-input-error :messages="$errors->get('title_en')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="type" value="Type d'événement" />
        <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
            @foreach (\App\Models\Evenement::TYPES as $key => $label)
                <option value="{{ $key }}" @selected(old('type', $evenement?->type ?? 'panel') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <x-input-label for="slug" value="Slug (URL, laisser vide pour auto)" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" value="{{ old('slug', $evenement?->slug) }}" />
        <x-input-error :messages="$errors->get('slug')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="starts_at" value="Début" />
        <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full"
                      value="{{ old('starts_at', $dt($evenement?->starts_at)) }}" required />
        <x-input-error :messages="$errors->get('starts_at')" class="mt-1" />
    </div>
    <div>
        <x-input-label for="ends_at" value="Fin (facultatif)" />
        <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full"
                      value="{{ old('ends_at', $dt($evenement?->ends_at)) }}" />
        <x-input-error :messages="$errors->get('ends_at')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="antenne_id" value="Antenne (facultatif)" />
        <select id="antenne_id" name="antenne_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
            <option value="">— Événement général —</option>
            @foreach ($antennes as $antenne)
                <option value="{{ $antenne->id }}" @selected((string) old('antenne_id', $evenement?->antenne_id) === (string) $antenne->id)>{{ $antenne->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <x-input-label for="location" value="Lieu précis (salle, adresse…)" />
        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" value="{{ old('location', $evenement?->location) }}" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="online_url" value="Lien en ligne (webinaire, facultatif)" />
        <x-text-input id="online_url" name="online_url" type="url" class="mt-1 block w-full" value="{{ old('online_url', $evenement?->online_url) }}" />
        <x-input-error :messages="$errors->get('online_url')" class="mt-1" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="image" value="Image / affiche" />
        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        <x-input-error :messages="$errors->get('image')" class="mt-1" />
        @if ($evenement?->image)
            <img src="{{ asset('storage/'.$evenement->image) }}" alt="" class="mt-2 h-24 rounded object-cover">
        @endif
    </div>

    <div>
        <x-input-label for="description_fr" value="Description (FR)" />
        <textarea id="description_fr" name="description_fr" rows="8"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('description_fr', $evenement?->description_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="description_en" value="Description (EN)" />
        <textarea id="description_en" name="description_en" rows="8"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('description_en', $evenement?->description_en) }}</textarea>
    </div>
</div>

{{-- Journées / parcours au choix --}}
<div x-data="{ rows: {{ \Illuminate\Support\Js::from(array_values($sessionRows)) }} }" class="border border-gray-200 rounded-lg p-4">
    <div class="flex items-start justify-between gap-4 mb-3">
        <div>
            <h3 class="font-semibold text-gray-900">Journées / parcours au choix</h3>
            <p class="text-xs text-gray-500">Ex. « Jour 1 : Panel sur le Digital », « Jour 2 : Tourisme Industriel ». L'inscrit coche ceux auxquels il participe. Laissez vide si l'événement n'a qu'une seule séance. Capacité vide = illimitée.</p>
        </div>
        <button type="button" @click="rows.push({ id: null, title_fr: '', title_en: '', starts_at: '', capacity: '' })"
                class="shrink-0 px-3 py-1.5 text-sm bg-gray-100 rounded-md hover:bg-gray-200">+ Ajouter</button>
    </div>

    <template x-for="(row, i) in rows" :key="i">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-3 items-start bg-gray-50 rounded-md p-3">
            <input type="hidden" :name="`sessions[${i}][id]`" :value="row.id">
            <input type="text" :name="`sessions[${i}][title_fr]`" x-model="row.title_fr" placeholder="Titre (FR)" required
                   class="md:col-span-3 border-gray-300 rounded-md text-sm focus:border-epa-red focus:ring-epa-red">
            <input type="text" :name="`sessions[${i}][title_en]`" x-model="row.title_en" placeholder="Titre (EN)"
                   class="md:col-span-3 border-gray-300 rounded-md text-sm focus:border-epa-red focus:ring-epa-red">
            <input type="datetime-local" :name="`sessions[${i}][starts_at]`" x-model="row.starts_at"
                   class="md:col-span-3 border-gray-300 rounded-md text-sm focus:border-epa-red focus:ring-epa-red">
            <input type="number" min="1" :name="`sessions[${i}][capacity]`" x-model="row.capacity" placeholder="Places"
                   class="md:col-span-2 border-gray-300 rounded-md text-sm focus:border-epa-red focus:ring-epa-red">
            <button type="button" @click="rows.splice(i, 1)" class="md:col-span-1 text-epa-red text-sm hover:underline text-left md:text-center">Retirer</button>
        </div>
    </template>
    <p x-show="rows.length === 0" class="text-sm text-gray-400">Aucune journée définie.</p>
    <x-input-error :messages="$errors->get('sessions.*')" class="mt-1" />
</div>

<div class="flex flex-wrap items-center gap-x-8 gap-y-3">
    <label class="flex items-center gap-2 text-sm">
        <input name="published" type="checkbox" value="1" class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               @checked(old('published', $evenement?->published ?? false))>
        Publier sur le site public
    </label>
    <label class="flex items-center gap-2 text-sm">
        <input name="registration_open" type="checkbox" value="1" class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               @checked(old('registration_open', $evenement?->registration_open ?? true))>
        Inscriptions ouvertes
    </label>
    <label class="flex items-center gap-2 text-sm">
        <input name="is_featured" type="checkbox" value="1" class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               @checked(old('is_featured', $evenement?->is_featured ?? false))>
        Mettre en avant
    </label>
</div>
