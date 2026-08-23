@props(['title'])

@php
    $shareUrl = urlencode(url()->current());
    $shareTitle = urlencode($title);
@endphp

<div class="flex items-center gap-2">
    <span class="text-xs text-gray-500 font-medium">Partager :</span>

    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"
       class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-epa-red hover:text-white transition"
       aria-label="Partager sur Facebook" title="Partager sur Facebook">
        <x-social-icon platform="facebook" class="w-3.5 h-3.5" />
    </a>

    <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener"
       class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-green-500 hover:text-white transition"
       aria-label="Partager sur WhatsApp" title="Partager sur WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.39 1.26 4.81L2 22l5.42-1.36a9.9 9.9 0 0 0 4.62 1.14h.01c5.46 0 9.9-4.45 9.9-9.91S17.5 2 12.04 2Zm5.79 14.06c-.24.68-1.4 1.3-1.94 1.35-.5.05-1.02.24-3.4-.71-2.88-1.15-4.72-4.06-4.86-4.25-.14-.19-1.16-1.54-1.16-2.94s.73-2.09.99-2.38c.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.81 1.99.88 2.13.07.14.12.31.02.5-.1.19-.15.31-.29.48-.14.17-.31.38-.44.51-.15.15-.3.31-.13.6.17.29.76 1.25 1.62 2.03 1.12 1 2.07 1.31 2.36 1.46.29.15.46.13.63-.08.17-.21.72-.84.91-1.13.19-.29.38-.24.63-.14.26.1 1.65.78 1.93.92.29.14.48.21.55.33.07.12.07.7-.17 1.38Z"/>
        </svg>
    </a>

    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener"
       class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-epa-red hover:text-white transition"
       aria-label="Partager sur LinkedIn" title="Partager sur LinkedIn">
        <x-social-icon platform="linkedin" class="w-3.5 h-3.5" />
    </a>
</div>
