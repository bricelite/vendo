<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($boutique) ? $boutique->nom.' — Vendo' : config('app.name', 'Vendo') }}</title>

        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-texte antialiased" style="background: #FBF7EE" data-boutique-id="{{ $boutique->id }}">
        <div id="vendo-toasts" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-sm space-y-2 pointer-events-none"></div>
        @php
            $noteMoyenneBoutique = \App\Models\Avis::whereHas('produit', fn ($q) => $q->where('boutique_id', $boutique->id))->avg('note');
            $nombreAvisBoutique = \App\Models\Avis::whereHas('produit', fn ($q) => $q->where('boutique_id', $boutique->id))->count();
        @endphp
        {{-- Header boutique --}}
        <header style="background: linear-gradient(160deg, #1F3A5F, #16283F)">
            <div class="max-w-3xl mx-auto px-4 pt-5 pb-5">
                {{-- Ligne du haut : logo + panier --}}
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        @if ($boutique->logo_url)
                            <img src="{{ '/uploads/'.$boutique->logo_url }}" alt="{{ $boutique->nom }}"
                                 class="h-[34px] w-[34px] rounded-[9px] object-cover">
                        @else
                            <div class="h-[34px] w-[34px] rounded-[9px] flex items-center justify-center font-fraunces font-semibold text-lg"
                                 style="background: #C08A2E; color: #1F3A5F">
                                {{ mb_substr($boutique->nom, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <a href="{{ route('boutique-publique.accueil', $boutique) }}" class="font-fraunces font-semibold text-base" style="color: #FBF7EE">
                                {{ $boutique->nom }}
                            </a>
                            @if ($nombreAvisBoutique > 0)
                                <div class="flex items-center gap-1" style="font-size: 11px; color: #F0C878">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <span>{{ number_format($noteMoyenneBoutique, 1, ',', '.') }} ({{ $nombreAvisBoutique }} avis)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('panier', $boutique) }}" class="relative" style="color: #FBF7EE" x-data>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        <span x-show="$store.panier.nombreArticles() > 0"
                              x-text="$store.panier.nombreArticles()"
                              class="absolute -top-1.5 -right-1.5 inline-flex items-center justify-center h-[15px] min-w-[15px] px-0.5 rounded-full text-xs font-bold"
                              style="background: #C08A2E; color: #1F3A5F; font-size: 10px">
                        </span>
                    </a>
                </div>

                {{-- Description --}}
                @if ($boutique->description)
                    <p style="font-size: 11px; color: #B9C4D4" class="mb-1">{{ $boutique->description }}</p>
                @endif
            </div>
        </header>

        <main class="max-w-3xl mx-auto px-4 pb-24">
            {{ $slot }}
        </main>

        <footer style="border-top: 1px solid #E7DFCB">
            <div class="max-w-3xl mx-auto px-4 py-4 text-center text-xs" style="color: #8A7550">
                Propulsé par <a href="/" class="font-semibold" style="color: #1F3A5F">Vendo</a>
            </div>
        </footer>

        @php
            // Format international requis par WhatsApp (indicatif du Bénin)
            $telephoneWhatsApp = preg_replace('/[^0-9]/', '', $boutique->vendeur->telephone);
            if (! str_starts_with($telephoneWhatsApp, '229')) {
                $telephoneWhatsApp = '229'.$telephoneWhatsApp;
            }
        @endphp
        {{-- Contact direct vendeur : positionné au-dessus de la barre « Commander » sur mobile --}}
        <a href="https://wa.me/{{ $telephoneWhatsApp }}?text={{ rawurlencode("Bonjour, j'ai une question sur vos produits.") }}"
           target="_blank" rel="noopener" aria-label="Contacter la boutique sur WhatsApp"
           class="fixed bottom-24 right-4 z-20 h-14 w-14 rounded-full bg-succes text-white shadow-lg flex items-center justify-center">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 002.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </a>
    </body>
</html>
