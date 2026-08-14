@php $session ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="formation_id" value="Formation" />
        <select id="formation_id" name="formation_id" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
            <option value="">— Choisir —</option>
            @foreach ($formations as $formation)
                <option value="{{ $formation->id }}" @selected(old('formation_id', $session?->formation_id) == $formation->id)>
                    {{ $formation->title_fr }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="antenne_id" value="Antenne" />
        <select id="antenne_id" name="antenne_id" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
            <option value="">— Choisir —</option>
            @foreach ($antennes as $antenne)
                <option value="{{ $antenne->id }}" @selected(old('antenne_id', $session?->antenne_id) == $antenne->id)>
                    {{ $antenne->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="start_date" value="Date de début" />
        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                       value="{{ old('start_date', $session?->start_date?->format('Y-m-d')) }}" required />
    </div>

    <div>
        <x-input-label for="end_date" value="Date de fin (optionnel)" />
        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                       value="{{ old('end_date', $session?->end_date?->format('Y-m-d')) }}" />
    </div>

    <div>
        <x-input-label for="modality" value="Modalité" />
        <select id="modality" name="modality" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
            @foreach (['en_ligne' => 'En ligne', 'presentiel_jour' => 'Présentiel - jour', 'presentiel_soir' => 'Présentiel - soir'] as $value => $label)
                <option value="{{ $value }}" @selected(old('modality', $session?->modality) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="status" value="Statut" />
        <select id="status" name="status" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
            @foreach (['ouverte' => 'Ouverte', 'complete' => 'Complète', 'cloturee' => 'Clôturée'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $session?->status ?? 'ouverte') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="capacity" value="Places totales" />
        <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full"
                       value="{{ old('capacity', $session?->capacity) }}" />
    </div>

    <div>
        <x-input-label for="seats_taken" value="Places déjà prises" />
        <x-text-input id="seats_taken" name="seats_taken" type="number" class="mt-1 block w-full"
                       value="{{ old('seats_taken', $session?->seats_taken ?? 0) }}" />
    </div>
</div>
