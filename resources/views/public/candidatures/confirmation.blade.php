<x-public-layout title="Candidature envoyée">
    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-6">
            <span class="text-green-600 text-2xl">✓</span>
        </div>
        <h1 class="text-2xl font-bold mb-4">Votre candidature a bien été envoyée !</h1>
        <p class="text-gray-600 mb-2">
            Merci {{ $candidature->first_name }}, nous avons bien reçu votre candidature pour la formation
            <strong>{{ $candidature->formation->title_fr }}</strong>.
        </p>
        <p class="text-gray-600 mb-8">
            Un email de confirmation vous a été envoyé à <strong>{{ $candidature->email }}</strong>.
            Notre équipe vous contactera prochainement.
        </p>

        <div class="p-4 rounded-lg bg-gray-50 mb-8">
            <p class="text-sm text-gray-500 mb-2">Conservez ce lien pour suivre votre candidature :</p>
            <a href="{{ route('candidatures.track', $candidature->tracking_token) }}" class="text-epa-red text-sm font-medium break-all">
                {{ route('candidatures.track', $candidature->tracking_token) }}
            </a>
        </div>

        <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-epa-red">&larr; Retour à l'accueil</a>
    </section>
</x-public-layout>
