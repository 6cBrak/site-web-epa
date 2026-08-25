@php use App\Models\Setting; @endphp
<x-public-layout :title="Setting::text('contact_title')" description="Contactez EPA_BURKINA : nos antennes à Ouagadougou, Bobo-Dioulasso et Dori/Sahel, téléphone, email et formulaire de contact en ligne.">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold mb-6 text-center">{{ Setting::text('contact_title') }}</h1>

        <div class="max-w-3xl mx-auto mb-12 bg-red-50 border border-epa-red/20 rounded-xl p-5 flex items-center justify-between gap-4 flex-wrap">
            <div>
                <p class="font-medium text-epa-black">Une question rapide ?</p>
                <p class="text-sm text-gray-600">Discutez tout de suite avec notre assistante en ligne, pas besoin d'attendre une réponse par email.</p>
            </div>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-assistant-chat'))"
                    class="px-5 py-2.5 rounded-md bg-epa-red text-white text-sm font-semibold hover:opacity-90 transition shrink-0">
                Ouvrir le chat
            </button>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h2 class="font-semibold mb-4">{{ Setting::text('contact_antennes_title') }}</h2>
                <div class="space-y-8">
                    @foreach ($antennes as $antenne)
                        <div class="pb-8 border-b border-gray-100 last:border-0 last:pb-0">
                            <h3 class="font-medium">{{ $antenne->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $antenne->address }}</p>

                            @if ($antenne->phone)
                                <div class="flex items-center gap-2 mt-1.5">
                                    <a href="tel:{{ preg_replace('/\s+/', '', $antenne->phone) }}" class="text-sm text-gray-700 hover:text-epa-red">
                                        {{ $antenne->phone }}
                                    </a>
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $antenne->phone) }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-500 text-white hover:bg-green-600 transition shrink-0"
                                       aria-label="Contacter {{ $antenne->name }} sur WhatsApp" title="WhatsApp">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.39 1.26 4.81L2 22l5.42-1.36a9.9 9.9 0 0 0 4.62 1.14h.01c5.46 0 9.9-4.45 9.9-9.91S17.5 2 12.04 2Zm5.79 14.06c-.24.68-1.4 1.3-1.94 1.35-.5.05-1.02.24-3.4-.71-2.88-1.15-4.72-4.06-4.86-4.25-.14-.19-1.16-1.54-1.16-2.94s.73-2.09.99-2.38c.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.81 1.99.88 2.13.07.14.12.31.02.5-.1.19-.15.31-.29.48-.14.17-.31.38-.44.51-.15.15-.3.31-.13.6.17.29.76 1.25 1.62 2.03 1.12 1 2.07 1.31 2.36 1.46.29.15.46.13.63-.08.17-.21.72-.84.91-1.13.19-.29.38-.24.63-.14.26.1 1.65.78 1.93.92.29.14.48.21.55.33.07.12.07.7-.17 1.38Z"/>
                                        </svg>
                                    </a>
                                </div>
                            @endif

                            @if ($antenne->email)
                                <p class="text-sm text-gray-500 mt-1">
                                    <a href="mailto:{{ $antenne->email }}" class="hover:text-epa-red">{{ $antenne->email }}</a>
                                </p>
                            @endif

                            @php
                                $mapQuery = ($antenne->latitude && $antenne->longitude)
                                    ? $antenne->latitude.','.$antenne->longitude
                                    : $antenne->name.', '.$antenne->address;
                            @endphp
                            <div class="mt-3 rounded-lg overflow-hidden border border-gray-100">
                                <iframe
                                    src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&z=16&output=embed"
                                    width="100%" height="160" style="border:0;" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Carte — {{ $antenne->name }}">
                                </iframe>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h2 class="font-semibold mb-4">{{ Setting::text('contact_form_title') }}</h2>
                <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="name" value="Nom" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="subject" value="Sujet" />
                        <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" value="{{ old('subject') }}" required />
                        <x-input-error :messages="$errors->get('subject')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="message" value="Message" />
                        <textarea id="message" name="message" rows="5" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-1" />
                    </div>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 rounded-md bg-epa-red text-white text-sm font-semibold hover:opacity-90 transition">
                        Envoyer
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-public-layout>
