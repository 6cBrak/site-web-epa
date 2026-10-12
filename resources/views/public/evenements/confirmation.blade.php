<x-public-layout title="Inscription confirmée" :noindex="true">
    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-6">
            <span class="text-green-600 text-2xl">✓</span>
        </div>
        <h1 class="text-2xl font-bold mb-4">Votre inscription est confirmée !</h1>
        <p class="text-gray-600 mb-2">
            Merci {{ $inscription->full_name }}, vous êtes inscrit(e) à <strong>{{ $inscription->evenement->title }}</strong>.
        </p>

        <div class="p-4 rounded-lg bg-gray-50 my-8 text-sm text-left space-y-1">
            <div><span class="text-gray-500">Date :</span> {{ $inscription->evenement->starts_at->translatedFormat('l d F Y, H:i') }}</div>
            @if ($inscription->evenement->placeLabel())
                <div><span class="text-gray-500">Lieu :</span> {{ $inscription->evenement->placeLabel() }}</div>
            @endif
            @if ($inscription->sessions->isNotEmpty())
                <div><span class="text-gray-500">Participation :</span> {{ $inscription->sessions->pluck('title')->implode(' + ') }}</div>
            @endif
        </div>

        <p class="text-gray-600 mb-8">
            Nous vous relancerons sur WhatsApp au <strong>{{ $inscription->phone }}</strong> à l'approche de l'événement.
            @if ($inscription->email)
                Un email de confirmation a été envoyé à <strong>{{ $inscription->email }}</strong>.
            @endif
        </p>

        <div class="flex flex-wrap justify-center gap-3 mb-8">
            <a href="{{ route('evenements.ics', $inscription->evenement) }}" class="px-5 py-2.5 bg-epa-red text-white text-sm font-semibold rounded-md hover:opacity-90">
                Ajouter à mon calendrier
            </a>
            <a href="{{ route('evenements.show', $inscription->evenement) }}" class="px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-md hover:border-epa-red hover:text-epa-red">
                Voir l'événement
            </a>
        </div>

        <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-epa-red">&larr; Retour à l'accueil</a>
    </section>
</x-public-layout>
