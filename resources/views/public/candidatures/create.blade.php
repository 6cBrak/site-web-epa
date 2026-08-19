<x-public-layout title="S'inscrire">
    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold mb-2 text-center">Formulaire d'inscription</h1>
        <p class="text-gray-500 text-center mb-10">Remplissez ce formulaire, nous revenons vers vous rapidement.</p>

        <form method="POST" action="{{ route('candidatures.store') }}" enctype="multipart/form-data"
              class="space-y-6 bg-white rounded-xl border border-gray-100 shadow-sm p-8"
              x-data="{
                  profileType: '{{ old('profile_type', 'etudiant') }}',
                  formationId: '{{ old('formation_id', $selectedFormationId) }}',
                  sessionsByFormation: {{ \Illuminate\Support\Js::from($sessionsByFormation) }},
                  get sessions() { return this.sessionsByFormation[this.formationId] ?? []; }
              }">
            @csrf

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="first_name" value="Prénom" />
                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" value="{{ old('first_name') }}" required />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="last_name" value="Nom" />
                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" value="{{ old('last_name') }}" required />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="phone" value="Téléphone (WhatsApp)" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone') }}" required />
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="education_level" value="Niveau d'étude / dernier diplôme" />
                    <x-text-input id="education_level" name="education_level" type="text" class="mt-1 block w-full" value="{{ old('education_level') }}" />
                </div>
                <div>
                    <x-input-label for="nationality" value="Nationalité" />
                    <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full" value="{{ old('nationality') }}" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="city_country" value="Ville / Pays de résidence" />
                    <x-text-input id="city_country" name="city_country" type="text" class="mt-1 block w-full" value="{{ old('city_country') }}" />
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="formation_id" value="Formation souhaitée" />
                    <select id="formation_id" name="formation_id" x-model="formationId" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                        <option value="">— Choisir —</option>
                        @foreach ($formations as $formation)
                            <option value="{{ $formation->id }}">{{ $formation->title }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('formation_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="antenne_id" value="Antenne souhaitée" />
                    <select id="antenne_id" name="antenne_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                        <option value="">— Choisir —</option>
                        @foreach ($antennes as $antenne)
                            <option value="{{ $antenne->id }}" @selected(old('antenne_id') == $antenne->id)>{{ $antenne->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('antenne_id')" class="mt-1" />
                </div>

                <div class="md:col-span-2" x-show="sessions.length > 0">
                    <x-input-label for="formation_session_id" value="Session (optionnel)" />
                    <select id="formation_session_id" name="formation_session_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                        <option value="">— Aucune session spécifique —</option>
                        <template x-for="session in sessions" :key="session.id">
                            <option :value="session.id" x-text="session.label"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <x-input-label for="start_preference" value="Quand souhaitez-vous commencer ?" />
                    <select id="start_preference" name="start_preference"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                        <option value="immediat">Immédiatement</option>
                        <option value="prochaine_rentree">Prochaine rentrée académique</option>
                        <option value="session_specialisee">Session spécialisée</option>
                    </select>
                </div>

                <div>
                    <x-input-label for="how_heard" value="Comment avez-vous connu EPA ?" />
                    <x-text-input id="how_heard" name="how_heard" type="text" class="mt-1 block w-full" value="{{ old('how_heard') }}" />
                </div>
            </div>

            <hr class="border-gray-100">

            <div>
                <x-input-label value="Vous êtes" />
                <div class="mt-2 flex gap-6">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="radio" name="profile_type" value="etudiant" x-model="profileType" class="text-epa-red focus:ring-epa-red">
                        Étudiant(e)
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="radio" name="profile_type" value="professionnel" x-model="profileType" class="text-epa-red focus:ring-epa-red">
                        Professionnel(le)
                    </label>
                </div>
            </div>

            <div x-show="profileType === 'professionnel'">
                <x-input-label for="cv" value="CV (PDF ou Word)" />
                <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx" class="mt-1 block w-full text-sm">
                <x-input-error :messages="$errors->get('cv')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="promo_code" value="Code promo / partenaire (optionnel)" />
                <x-text-input id="promo_code" name="promo_code" type="text" class="mt-1 block w-full" value="{{ old('promo_code') }}" />
                <x-input-error :messages="$errors->get('promo_code')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="comment" value="Commentaire / question (optionnel)" />
                <textarea id="comment" name="comment" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('comment') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full px-6 py-3 rounded-md bg-epa-red text-white font-semibold hover:opacity-90 transition">
                Envoyer ma candidature
            </button>
        </form>
    </section>
</x-public-layout>
