<nav class="sticky top-0 z-30 glass rounded-none border-t-0 border-x-0 md:hidden">
    <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between gap-4">
        <x-bouton-retour />

        @isset($header)
            <h1 class="flex-1 text-center font-semibold text-texte truncate">{{ $header }}</h1>
        @endisset

        <div class="flex items-center gap-1 shrink-0">
            @if (Auth::user()->boutique)
                <a href="{{ route('boutique-publique.accueil', Auth::user()->boutique) }}" target="_blank"
                   class="inline-flex items-center justify-center h-10 w-10 rounded-full text-texte-secondaire hover:text-principale hover:bg-principale/10"
                   title="Voir ma boutique">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375a1.125 1.125 0 011.125-1.125h3.75a1.125 1.125 0 011.125 1.125V21" />
                    </svg>
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center justify-center h-10 w-10 rounded-full text-texte-secondaire hover:text-alerte hover:bg-alerte/10"
                        title="Se déconnecter">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</nav>

{{-- Sidebar navigation pour desktop (>= 768px) --}}
<aside class="hidden md:flex md:flex-col md:fixed md:inset-y-0 md:left-0 md:z-30 md:w-60 glass rounded-none border-r-2 border-r-white/20" x-data>
    <div class="flex items-center gap-2 px-5 h-16 border-b border-white/10 shrink-0">
        <a href="{{ route('dashboard') }}">
            <span class="text-lg font-bold text-principale">Vendo</span>
        </a>
    </div>

    <nav class="flex-1 py-4 px-3 space-y-1">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                  {{ request()->routeIs('dashboard') ? 'glass-subtle text-principale' : 'text-texte-secondaire hover:bg-white/10 hover:text-texte' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
            </svg>
            Accueil
        </a>

        <a href="{{ route('produits.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                  {{ request()->routeIs('produits.*') ? 'glass-subtle text-principale' : 'text-texte-secondaire hover:bg-white/10 hover:text-texte' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
            </svg>
            Produits
        </a>

        <a href="{{ route('categories.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                  {{ request()->routeIs('categories.*') ? 'glass-subtle text-principale' : 'text-texte-secondaire hover:bg-white/10 hover:text-texte' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z" />
            </svg>
            Catégories
        </a>

        <a href="{{ route('commandes.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                  {{ request()->routeIs('commandes.*') ? 'glass-subtle text-principale' : 'text-texte-secondaire hover:bg-white/10 hover:text-texte' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            Commandes
        </a>

        <a href="{{ route('avis.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                  {{ request()->routeIs('avis.*') ? 'glass-subtle text-principale' : 'text-texte-secondaire hover:bg-white/10 hover:text-texte' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
            </svg>
            Avis
        </a>

        <div class="pt-3 mt-3 border-t border-white/10">
            <a href="{{ route('profile.boutique') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('profile.*') ? 'glass-subtle text-principale' : 'text-texte-secondaire hover:bg-white/10 hover:text-texte' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Profil
            </a>
        </div>
    </nav>

    <div class="p-3 border-t border-white/10">
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="h-8 w-8 rounded-full bg-principale/10 flex items-center justify-center text-principale font-semibold text-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-texte truncate">{{ Auth::user()->name }}</p>
                @if (Auth::user()->boutique)
                    <a href="{{ route('boutique-publique.accueil', Auth::user()->boutique) }}" target="_blank"
                       class="text-xs text-texte-secondaire hover:text-principale truncate block">Voir ma boutique</a>
                @endif
            </div>

            {{-- Notification bell (à brancher plus tard) --}}
            <span class="relative inline-flex items-center justify-center h-8 w-8 rounded-full text-texte-secondaire">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-texte-secondaire hover:text-alerte" title="Se déconnecter">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
