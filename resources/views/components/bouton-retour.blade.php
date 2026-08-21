<a href="{{ $href ?? '#' }}"
   onclick="event.preventDefault(); {{ ($href ?? null) ? "window.location.href='" . ($href ?? '#') . "'" : 'history.back()' }}; return false;"
   class="inline-flex items-center justify-center h-10 w-10 rounded-full text-texte hover:bg-fond-alterne shrink-0"
   {{ $attributes }}>
    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
    </svg>
</a>
