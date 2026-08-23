@php use App\Models\Setting; use Illuminate\Support\Str; @endphp
<x-public-layout :title="Setting::text('about_title')" :description="Str::limit(strip_tags(Setting::text('about_intro')), 160)">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold mb-8 text-center">{{ Setting::text('about_title') }}</h1>

        <p class="text-gray-600 leading-relaxed mb-10">
            {{ Setting::text('about_intro') }}
        </p>

        <div class="grid md:grid-cols-2 gap-8 mb-16">
            <div class="p-6 rounded-xl bg-gray-50">
                <h2 class="font-semibold text-epa-red mb-2">{{ Setting::text('about_vision_title') }}</h2>
                <p class="text-sm text-gray-600">
                    {{ Setting::text('about_vision_text') }}
                </p>
            </div>
            <div class="p-6 rounded-xl bg-gray-50">
                <h2 class="font-semibold text-epa-red mb-2">{{ Setting::text('about_mission_title') }}</h2>
                <p class="text-sm text-gray-600">
                    {{ Setting::text('about_mission_text') }}
                </p>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-6 text-center">{{ Setting::text('about_antennes_title') }}</h2>
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
