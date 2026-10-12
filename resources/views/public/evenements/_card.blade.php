<a href="{{ route('evenements.show', $evenement) }}"
   class="group flex flex-col bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition overflow-hidden {{ ($past ?? false) ? 'opacity-80' : '' }}">
    <div class="relative h-40 bg-gray-100 overflow-hidden">
        @if ($evenement->image)
            <img src="{{ asset('storage/'.$evenement->image) }}" alt="{{ $evenement->title }}" class="w-full h-full object-cover" loading="lazy">
        @endif
        <div class="absolute top-3 left-3 bg-white rounded-lg shadow px-3 py-1.5 text-center leading-tight">
            <div class="text-xl font-bold text-epa-red">{{ $evenement->starts_at->format('d') }}</div>
            <div class="text-xs uppercase text-gray-600">{{ $evenement->starts_at->translatedFormat('M Y') }}</div>
        </div>
        @if ($evenement->is_featured && ! $evenement->isPast())
            <span class="absolute top-3 right-3 bg-epa-red text-white text-xs font-semibold px-2 py-1 rounded">À la une</span>
        @endif
    </div>
    <div class="p-5 flex-1 flex flex-col">
        <div class="text-xs text-epa-red font-medium mb-1">{{ $evenement->type_label }}</div>
        <h3 class="font-semibold mb-2 group-hover:text-epa-red transition">{{ $evenement->title }}</h3>
        <div class="text-sm text-gray-500 mt-auto">
            {{ $evenement->starts_at->translatedFormat('l d F Y, H:i') }}
            @if ($evenement->placeLabel())
                <br>{{ $evenement->placeLabel() }}
            @endif
        </div>
    </div>
</a>
