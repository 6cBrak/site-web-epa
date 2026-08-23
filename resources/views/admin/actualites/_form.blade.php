@php $actualite ??= null; @endphp

<div
    x-data="actualiteAiWriter()"
    class="bg-red-50/50 border border-epa-red/20 rounded-lg p-4 mb-6"
>
    <label for="ai_subject" class="block text-sm font-medium text-epa-black mb-1">
        ✨ Générer un brouillon avec l'IA
    </label>
    <p class="text-xs text-gray-500 mb-2">Décris le sujet en quelques mots (ex: "Remise de diplômes de la promotion 2026 à Ouagadougou"), l'IA rédige un brouillon FR/EN à relire ci-dessous.</p>
    <div class="flex gap-2">
        <input id="ai_subject" type="text" x-model="subject" :disabled="loading"
               class="flex-1 border-gray-300 rounded-md shadow-sm text-sm focus:border-epa-red focus:ring-epa-red"
               placeholder="Sujet de l'article...">
        <button type="button" @click="generate()" :disabled="loading || !subject.trim()"
                class="px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90 disabled:opacity-40 shrink-0">
            <span x-show="!loading">Générer</span>
            <span x-show="loading">Génération…</span>
        </button>
    </div>
    <p x-show="error" x-text="error" x-cloak class="text-xs text-red-600 mt-2"></p>
    <p x-show="filled" x-cloak class="text-xs text-green-700 mt-2">✓ Brouillon inséré ci-dessous — relis et ajuste avant d'enregistrer.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="title_fr" value="Titre (FR)" />
        <x-text-input id="title_fr" name="title_fr" type="text" class="mt-1 block w-full"
                       value="{{ old('title_fr', $actualite?->title_fr) }}" required autofocus />
    </div>
    <div>
        <x-input-label for="title_en" value="Titre (EN)" />
        <x-text-input id="title_en" name="title_en" type="text" class="mt-1 block w-full"
                       value="{{ old('title_en', $actualite?->title_en) }}" required />
    </div>

    <div>
        <x-input-label for="slug" value="Slug (URL, laisser vide pour auto)" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full"
                       value="{{ old('slug', $actualite?->slug) }}" />
    </div>

    <div>
        <x-input-label for="image" value="Image" />
        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        @if ($actualite?->image)
            <img src="{{ asset('storage/'.$actualite->image) }}" alt="" class="mt-2 h-24 rounded object-cover">
        @endif
    </div>

    <div>
        <x-input-label for="excerpt_fr" value="Extrait (FR)" />
        <textarea id="excerpt_fr" name="excerpt_fr" rows="2"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('excerpt_fr', $actualite?->excerpt_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="excerpt_en" value="Extrait (EN)" />
        <textarea id="excerpt_en" name="excerpt_en" rows="2"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('excerpt_en', $actualite?->excerpt_en) }}</textarea>
    </div>

    <div>
        <x-input-label for="content_fr" value="Contenu (FR)" />
        <textarea id="content_fr" name="content_fr" rows="8"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('content_fr', $actualite?->content_fr) }}</textarea>
    </div>
    <div>
        <x-input-label for="content_en" value="Contenu (EN)" />
        <textarea id="content_en" name="content_en" rows="8"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">{{ old('content_en', $actualite?->content_en) }}</textarea>
    </div>

    <div class="flex items-center gap-2">
        <input id="published" name="published" type="checkbox" value="1"
               class="rounded border-gray-300 text-epa-red focus:ring-epa-red"
               {{ old('published', filled($actualite?->published_at)) ? 'checked' : '' }}>
        <x-input-label for="published" value="Publier sur le site public" />
    </div>
</div>

<script>
    function actualiteAiWriter() {
        return {
            subject: '',
            loading: false,
            error: '',
            filled: false,

            async generate() {
                if (!this.subject.trim() || this.loading) return;

                this.loading = true;
                this.error = '';
                this.filled = false;

                try {
                    const response = await fetch('{{ route('admin.actualites.ai-generate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ subject: this.subject }),
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        this.error = data.message || "Échec de la génération.";
                        return;
                    }

                    ['title_fr', 'title_en', 'excerpt_fr', 'excerpt_en', 'content_fr', 'content_en'].forEach((field) => {
                        const el = document.getElementById(field);
                        if (el && data[field]) el.value = data[field];
                    });

                    this.filled = true;
                } catch (e) {
                    this.error = "Erreur réseau, réessayez.";
                } finally {
                    this.loading = false;
                }
            },
        };
    }
</script>
