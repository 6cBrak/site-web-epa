<x-public-layout title="Qui sommes-nous">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold mb-8 text-center">Qui sommes-nous ?</h1>

        <p class="text-gray-600 leading-relaxed mb-10">
            <strong>EPA_BURKINA</strong> est un centre de formation professionnelle spécialisé dans les métiers de
            l'Informatique &amp; de l'Action Humanitaire. Implanté au Burkina Faso depuis plusieurs années, EPA
            répond aux besoins croissants en compétences qualifiées des ONG, associations et entreprises qui
            contribuent au développement du pays. Grâce à son équipe de consultants formateurs nationaux et
            internationaux, EPA met à disposition des experts de chaque domaine qui transmettent leur savoir-faire
            et leurs expériences, pour garantir des formations professionnelles qualifiantes de qualité et orientées
            vers le développement de chaque apprenant.
        </p>

        <div class="grid md:grid-cols-2 gap-8 mb-16">
            <div class="p-6 rounded-xl bg-gray-50">
                <h2 class="font-semibold text-epa-red mb-2">Vision</h2>
                <p class="text-sm text-gray-600">
                    Devenir un acteur de référence en matière de formation innovante et technique dans les domaines
                    de l'action humanitaire et de l'informatique sur le continent africain.
                </p>
            </div>
            <div class="p-6 rounded-xl bg-gray-50">
                <h2 class="font-semibold text-epa-red mb-2">Mission</h2>
                <p class="text-sm text-gray-600">
                    Former à travers toute l'Afrique des jeunes professionnels compétents, éthiques et responsables,
                    capables d'agir efficacement face aux enjeux majeurs du continent tout en contribuant activement
                    à son développement durable.
                </p>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-6 text-center">Nos antennes</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($antennes as $antenne)
                <div class="p-5 rounded-xl border border-gray-100">
                    <h3 class="font-semibold mb-1">{{ $antenne->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $antenne->address }}</p>
                    @if ($antenne->phone)
                        <p class="text-sm text-gray-500 mt-2">{{ $antenne->phone }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
</x-public-layout>
