<x-admin-layout title="Textes du site">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Logo</h2>
            <div class="flex items-center gap-6">
                <div class="h-20 w-40 rounded-md border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden">
                    <img src="{{ $logoUrl }}" alt="Logo actuel" class="max-h-full max-w-full object-contain">
                </div>
                <div class="flex-1">
                    <x-input-label for="logo" value="Remplacer le logo" />
                    <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm">
                    <p class="mt-1 text-xs text-gray-500">Fond blanc ou transparent recommandé, format large (comme le logo actuel).</p>
                    <x-input-error :messages="$errors->get('logo')" class="mt-1" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Réglages généraux</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="hero_slide_delay_seconds" value="Délai entre les images du diaporama d'accueil (secondes)" />
                    <x-text-input id="hero_slide_delay_seconds" name="hero_slide_delay_seconds" type="number" step="0.5" min="1" max="30"
                                   class="mt-1 block w-full" value="{{ old('hero_slide_delay_seconds', $heroSlideDelay) }}" required />
                    <x-input-error :messages="$errors->get('hero_slide_delay_seconds')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="chat_assistant_name" value="Prénom de l'assistante du chat" />
                    <x-text-input id="chat_assistant_name" name="chat_assistant_name" type="text" maxlength="50"
                                   class="mt-1 block w-full" value="{{ old('chat_assistant_name', $chatAssistantName) }}" required />
                    <p class="mt-1 text-xs text-gray-500">Utilisé dans le widget de chat du site et dans les réponses de l'assistant IA.</p>
                    <x-input-error :messages="$errors->get('chat_assistant_name')" class="mt-1" />
                </div>
            </div>
        </div>

        @foreach ($groups as $groupLabel => $fields)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">{{ $groupLabel }}</h2>
                <div class="space-y-5">
                    @foreach ($fields as $key => [$label, $type])
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="{{ $key }}_fr" value="{{ $label }} — FR" />
                                @if ($type === 'textarea')
                                    <textarea id="{{ $key }}_fr" name="{{ $key }}_fr" rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old($key.'_fr', $values[$key.'_fr']) }}</textarea>
                                @else
                                    <x-text-input id="{{ $key }}_fr" name="{{ $key }}_fr" type="text" class="mt-1 block w-full"
                                                   value="{{ old($key.'_fr', $values[$key.'_fr']) }}" />
                                @endif
                            </div>
                            <div>
                                <x-input-label for="{{ $key }}_en" value="{{ $label }} — EN" />
                                @if ($type === 'textarea')
                                    <textarea id="{{ $key }}_en" name="{{ $key }}_en" rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old($key.'_en', $values[$key.'_en']) }}</textarea>
                                @else
                                    <x-text-input id="{{ $key }}_en" name="{{ $key }}_en" type="text" class="mt-1 block w-full"
                                                   value="{{ old($key.'_en', $values[$key.'_en']) }}" />
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end sticky bottom-0 bg-gray-100/80 backdrop-blur py-3">
            <button type="submit" class="px-6 py-2.5 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
                Enregistrer les textes
            </button>
        </div>
    </form>
</x-admin-layout>
