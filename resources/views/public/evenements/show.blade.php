@php
    use Illuminate\Support\Str;

    $description = Str::limit(strip_tags((string) $evenement->description), 160)
        ?: $evenement->title.' — '.$evenement->starts_at->translatedFormat('d F Y');
    $image = $evenement->image ? asset('storage/'.$evenement->image) : null;

    $eventSchema = json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $evenement->title,
        'description' => $description,
        'startDate' => $evenement->starts_at->toIso8601String(),
        'endDate' => $evenement->ends_at?->toIso8601String(),
        'eventAttendanceMode' => $evenement->online_url && ! $evenement->placeLabel()
            ? 'https://schema.org/OnlineEventAttendanceMode'
            : 'https://schema.org/OfflineEventAttendanceMode',
        'location' => $evenement->placeLabel() ? [
            '@type' => 'Place',
            'name' => $evenement->location ?: $evenement->antenne?->name,
            'address' => $evenement->antenne?->address ?: $evenement->placeLabel(),
        ] : ($evenement->online_url ? ['@type' => 'VirtualLocation', 'url' => $evenement->online_url] : null),
        'image' => $image,
        'organizer' => ['@type' => 'EducationalOrganization', 'name' => 'EPA_BURKINA'],
    ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $selectedSessions = collect(old('sessions', []))->map(fn ($v) => (int) $v)->all();
@endphp
<x-public-layout :title="$evenement->title" :description="$description" :image="$image" :schema="$eventSchema">
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <a href="{{ route('evenements.index') }}" class="text-sm text-gray-500 hover:text-epa-red">&larr; Tous les événements</a>

        <div class="flex items-center justify-between gap-3 flex-wrap mt-4 mb-2">
            <span class="text-xs text-epa-red font-semibold uppercase">{{ $evenement->type_label }}</span>
            <x-share-buttons :title="$evenement->title" />
        </div>
        <h1 class="text-3xl font-bold mb-6">{{ $evenement->title }}</h1>

        @if ($evenement->image)
            <img src="{{ asset('storage/'.$evenement->image) }}" alt="{{ $evenement->title }}" class="w-full max-h-96 object-cover rounded-xl mb-8">
        @endif

        <div class="grid sm:grid-cols-2 gap-4 mb-8 p-5 rounded-xl bg-gray-50">
            <div>
                <div class="text-xs uppercase text-gray-500 mb-1">Date</div>
                <div class="font-medium">{{ $evenement->starts_at->translatedFormat('l d F Y') }}</div>
                <div class="text-sm text-gray-600">
                    {{ $evenement->starts_at->format('H:i') }}@if ($evenement->ends_at) — {{ $evenement->ends_at->isSameDay($evenement->starts_at) ? $evenement->ends_at->format('H:i') : $evenement->ends_at->translatedFormat('d F Y, H:i') }}@endif
                </div>
            </div>
            <div>
                <div class="text-xs uppercase text-gray-500 mb-1">Lieu</div>
                <div class="font-medium">{{ $evenement->placeLabel() ?? 'À préciser' }}</div>
                @if ($evenement->antenne?->address)
                    <div class="text-sm text-gray-600">{{ $evenement->antenne->address }}</div>
                @endif
                @if ($evenement->online_url && $evenement->canRegister())
                    <a href="{{ $evenement->online_url }}" target="_blank" rel="noopener" class="text-sm text-epa-red hover:underline">Lien de connexion</a>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-10">
            @if ($evenement->canRegister())
                <a href="#inscription" class="px-5 py-2.5 bg-epa-red text-white text-sm font-semibold rounded-md hover:opacity-90">Je m'inscris</a>
            @endif
            @unless ($evenement->isPast())
                <a href="{{ route('evenements.ics', $evenement) }}" class="px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-md hover:border-epa-red hover:text-epa-red">
                    Ajouter à mon calendrier
                </a>
            @endunless
        </div>

        @if ($evenement->description)
            <div class="prose max-w-none text-gray-700 whitespace-pre-line text-justify leading-relaxed mb-12">{{ $evenement->description }}</div>
        @endif

        @if ($evenement->sessions->isNotEmpty())
            <div class="mb-12">
                <h2 class="text-xl font-bold mb-4">Programme</h2>
                <ul class="space-y-3">
                    @foreach ($evenement->sessions as $session)
                        <li class="flex items-center justify-between gap-4 p-4 rounded-lg border border-gray-100">
                            <div>
                                <div class="font-medium">{{ $session->title }}</div>
                                @if ($session->starts_at)
                                    <div class="text-sm text-gray-500">{{ $session->starts_at->translatedFormat('l d F Y, H:i') }}</div>
                                @endif
                            </div>
                            @if ($session->capacity !== null)
                                <span class="text-xs shrink-0 {{ $session->isFull() ? 'text-red-600' : 'text-gray-500' }}">
                                    {{ $session->isFull() ? 'Complet' : $session->remainingPlaces().' place(s) restante(s)' }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="inscription" class="scroll-mt-24">
            @if (session('error'))
                <div class="mb-6 p-3 rounded-md bg-red-50 text-red-700 text-sm">{{ session('error') }}</div>
            @endif

            @if ($evenement->canRegister())
                <h2 class="text-xl font-bold mb-6">Inscription</h2>

                <form method="POST" action="{{ route('evenements.register', $evenement) }}"
                      class="space-y-6 bg-white rounded-xl border border-gray-100 shadow-sm p-6 sm:p-8"
                      x-data="{ epaStatus: '{{ old('epa_status', 'non') }}' }">
                    @csrf

                    {{-- honeypot anti-spam --}}
                    <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="full_name" value="Nom et prénom" />
                            <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" value="{{ old('full_name') }}" required />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="N° de téléphone (WhatsApp)" />
                            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" value="{{ old('phone') }}" required />
                            <p class="text-xs text-gray-400 mt-1">Nous l'utilisons pour vous relancer à l'approche de l'événement.</p>
                            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email (facultatif)" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}" />
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="profession" value="Profession" />
                            <x-text-input id="profession" name="profession" type="text" class="mt-1 block w-full" value="{{ old('profession') }}" required />
                            <x-input-error :messages="$errors->get('profession')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="nationality" value="Nationalité (facultatif)" />
                            <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full" value="{{ old('nationality') }}" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="city" value="Ville de résidence actuelle" />
                            <x-text-input id="city" name="city" type="text" list="villes" class="mt-1 block w-full" value="{{ old('city') }}" required />
                            <datalist id="villes">
                                <option value="Ouagadougou"><option value="Bobo-Dioulasso"><option value="Dori">
                            </datalist>
                            <x-input-error :messages="$errors->get('city')" class="mt-1" />
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <x-input-label value="Êtes-vous déjà inscrit(e) à EPA BURKINA ?" />
                        <div class="mt-2 space-y-2">
                            @foreach (\App\Models\EvenementInscription::EPA_STATUSES as $key => $label)
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="radio" name="epa_status" value="{{ $key }}" x-model="epaStatus"
                                           class="text-epa-red focus:ring-epa-red" required>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        <div x-show="epaStatus === 'autre'" x-cloak class="mt-2">
                            <x-text-input name="epa_status_other" type="text" class="block w-full" placeholder="Précisez" value="{{ old('epa_status_other') }}" />
                        </div>
                        <x-input-error :messages="$errors->get('epa_status')" class="mt-1" />
                        <x-input-error :messages="$errors->get('epa_status_other')" class="mt-1" />
                    </div>

                    @if ($evenement->sessions->isNotEmpty())
                        <div>
                            <x-input-label value="Participation" />
                            <div class="mt-2 space-y-2">
                                @foreach ($evenement->sessions as $session)
                                    <label class="flex items-center gap-2 text-sm {{ $session->isFull() ? 'opacity-50' : '' }}">
                                        <input type="checkbox" name="sessions[]" value="{{ $session->id }}"
                                               class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
                                               @checked(in_array($session->id, $selectedSessions, true)) @disabled($session->isFull())>
                                        {{ $session->title }}{{ $session->isFull() ? ' (complet)' : '' }}
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('sessions')" class="mt-1" />
                        </div>
                    @endif

                    <div>
                        <x-input-label for="how_heard" value="Comment avez-vous entendu parler d'EPA_BF et de cet événement ?" />
                        <x-text-input id="how_heard" name="how_heard" type="text" class="mt-1 block w-full" value="{{ old('how_heard') }}" required />
                        <x-input-error :messages="$errors->get('how_heard')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="comment" value="Questions, commentaires ou recommandations (facultatif)" />
                        <textarea id="comment" name="comment" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('comment') }}</textarea>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-epa-red text-white text-sm font-semibold rounded-md hover:opacity-90">
                        Confirmer mon inscription
                    </button>
                </form>
            @elseif (! $evenement->isPast())
                <div class="p-5 rounded-xl bg-gray-50 text-gray-600 text-sm">Les inscriptions à cet événement sont closes.</div>
            @endif
        </div>
    </section>
</x-public-layout>
