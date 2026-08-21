<nav class="sticky top-0 z-30 bg-fond border-b border-fond-alterne md:hidden">
    <div class="max-w-2xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
        <x-bouton-retour />

        @isset($header)
            <h1 class="flex-1 text-center font-semibold text-texte truncate">{{ $header }}</h1>
        @endisset

        <div class="flex items-center gap-1 shrink-0">
            @if (Auth::user()->boutique)
                <a href="{{ route('boutique-publique.accueil', Auth::user()->boutique) }}" target="_blank"
                   class="inline-flex items-center justify-center h-10 w-10 rounded-full text-texte-secondaire hover:text-principale hover:bg-fond-alterne"
                   title="Voir ma boutique">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375a1.125 1.125 0 011.125-1.125h3.75a1.125 1.125 0 011.125 1.125V21" />
                    </svg>
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center justify-center h-10 w-10 rounded-full text-texte-secondaire hover:text-alerte hover:bg-fond-alterne"
                        title="Se déconnecter">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</nav>
