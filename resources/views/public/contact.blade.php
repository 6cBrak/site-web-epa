@php use App\Models\Setting; @endphp
<x-public-layout :title="Setting::text('contact_title')" description="Contactez EPA_BURKINA : nos antennes à Ouagadougou, Bobo-Dioulasso et Dori/Sahel, téléphone, email et formulaire de contact en ligne.">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold mb-12 text-center">{{ Setting::text('contact_title') }}</h1>

        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h2 class="font-semibold mb-4">{{ Setting::text('contact_antennes_title') }}</h2>
                <div class="space-y-6">
                    @foreach ($antennes as $antenne)
                        <div>
                            <h3 class="font-medium">{{ $antenne->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $antenne->address }}</p>
                            @if ($antenne->phone)
                                <p class="text-sm text-gray-500">{{ $antenne->phone }}</p>
                            @endif
                            @if ($antenne->email)
                                <p class="text-sm text-gray-500">{{ $antenne->email }}</p>
                            @endif
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
