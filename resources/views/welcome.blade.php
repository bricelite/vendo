<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Vendo') }} — Transforme tes publications en ventes</title>

        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Page verrouillée sur un seul écran : aucun défilement */
            html, body {
                height: 100%;
                overflow: hidden;
            }
            #splash {
                position: fixed;
                inset: 0;
                z-index: 50;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: linear-gradient(160deg, #16305A, #1A4883);
                transition: opacity .5s ease, visibility .5s ease;
            }
            #splash.ferme {
                opacity: 0;
                visibility: hidden;
            }
            #splash .splash-text {
                opacity: 0;
                transform: scale(.9);
                animation: splash-fade .6s ease forwards;
            }
            #splash .barre {
                width: 180px;
                max-width: 60vw;
                height: 6px;
                border-radius: 9999px;
                background: rgba(255, 255, 255, .18);
                overflow: hidden;
                margin-top: 28px;
            }
            #splash .barre span {
                display: block;
                height: 100%;
                width: 0;
                border-radius: 9999px;
                background: rgba(255, 255, 255, .55);
                animation: splash-progress 2s ease forwards;
            }
            @keyframes splash-fade {
                to { opacity: 1; transform: scale(1); }
            }
            @keyframes splash-progress {
                to { width: 100%; }
            }
        </style>
    </head>
    <body class="font-sans text-texte antialiased overflow-hidden">

        {{-- Splash screen --}}
        <div id="splash" aria-hidden="true">
            <div class="splash-text">
                <span class="text-4xl font-bold text-white tracking-tight">Vendo</span>
            </div>
            <div class="barre"><span></span></div>
        </div>

        {{-- Nav flottante glass --}}
        <nav class="fixed top-0 inset-x-0 z-40 glass rounded-none rounded-b-2xl border-t-0 border-x-0">
            <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
                <a href="/" class="text-lg font-bold text-principale">Vendo</a>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 text-sm font-medium text-principale hover:text-white hover:bg-principale rounded-xl transition">
                            Tableau de bord
                        </a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                               class="px-4 py-2 text-sm font-medium text-principale hover:text-white hover:bg-principale rounded-xl transition">
                                Se connecter
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-5 py-2 text-sm font-semibold bg-accent text-white rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                Créer ma boutique
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </nav>

        {{-- Hero plein écran (un seul écran, pas de défilement) --}}
        <section class="relative h-screen flex items-center justify-center overflow-hidden"
                 style="background-image: url('/images/accueil.jpg'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-gradient-to-b from-principale/80 via-principale/60 to-principale/90"></div>

            <div class="relative z-10 max-w-3xl mx-auto px-5 pt-20 pb-16 text-center">
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight"
                    x-data="{ mots: ['WhatsApp', 'Instagram', 'Facebook'], index: 0 }"
                    x-init="setInterval(() => index = (index + 1) % mots.length, 2200)">
                    Ta boutique<br>
                    <span class="text-accent" x-text="mots[index]"></span>, enfin organisée.
                </h1>

                <p class="mt-5 text-base sm:text-lg text-white/80 max-w-lg mx-auto leading-relaxed">
                    Tu vends déjà sur tes réseaux ? Vendo te donne une boutique en ligne,
                    un suivi des commandes et un tableau de bord — tout simple.
                </p>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="mt-8 inline-flex items-center gap-2 px-8 py-4 bg-accent text-white font-bold text-base rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        Ouvrir mon tableau de bord
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="mt-8 inline-flex items-center gap-2 px-8 py-4 bg-accent text-white font-bold text-base rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        Créer ma boutique
                    </a>
                    <p class="mt-3 text-sm text-white/70">
                        Déjà inscrit ?
                        <a href="{{ route('login') }}" class="font-semibold text-white hover:underline">Se connecter</a>
                    </p>
                @endauth
            </div>
        </section>
    </body>
</html>
