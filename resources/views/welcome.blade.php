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
            #splash {
                position: fixed;
                inset: 0;
                z-index: 50;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: linear-gradient(160deg, #3B82F6, #2563EB);
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
                background: rgba(255, 255, 255, .25);
                overflow: hidden;
                margin-top: 28px;
            }
            #splash .barre span {
                display: block;
                height: 100%;
                width: 0;
                border-radius: 9999px;
                background: #EAB308;
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
    <body class="font-sans text-texte antialiased">

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

        {{-- Hero plein écran --}}
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden"
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

                {{-- Stats glass --}}
                <div class="mt-12 grid grid-cols-3 gap-3 max-w-sm mx-auto">
                    <div class="glass rounded-xl px-3 py-4 text-center">
                        <p class="text-xl sm:text-2xl font-bold text-white">0 <span class="text-sm font-normal">FCFA</span></p>
                        <p class="text-xs text-white/60 mt-1">pour démarrer</p>
                    </div>
                    <div class="glass rounded-xl px-3 py-4 text-center">
                        <p class="text-xl sm:text-2xl font-bold text-white">100%</p>
                        <p class="text-xs text-white/60 mt-1">Mobile Money</p>
                    </div>
                    <div class="glass rounded-xl px-3 py-4 text-center">
                        <p class="text-xl sm:text-2xl font-bold text-white">5 <span class="text-sm font-normal">min</span></p>
                        <p class="text-xs text-white/60 mt-1">pour démarrer</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Comment ça marche --}}
        <section class="py-20 px-5">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-2xl sm:text-3xl font-bold text-texte text-center mb-12 reveal"
                    x-intersect:enter="true" x-effect="$el.classList.toggle('visible', inView)">
                    Comment ça marche
                </h2>

                <div class="space-y-6">
                    <div class="glass-solid p-6 flex items-start gap-5 reveal"
                         x-intersect:enter="true" x-effect="$el.classList.toggle('visible', inView)">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-principale text-white flex items-center justify-center font-bold text-lg">
                            1
                        </div>
                        <div>
                            <h3 class="font-semibold text-texte text-lg">Publiez vos produits</h3>
                            <p class="text-sm text-texte-secondaire mt-1">Ajoutez vos articles avec photos, prix et stock en quelques secondes.</p>
                        </div>
                    </div>

                    <div class="glass-solid p-6 flex items-start gap-5 reveal"
                         x-intersect:enter="true" x-effect="$el.classList.toggle('visible', inView)">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-accent text-white flex items-center justify-center font-bold text-lg">
                            2
                        </div>
                        <div>
                            <h3 class="font-semibold text-texte text-lg">Encaissez les commandes</h3>
                            <p class="text-sm text-texte-secondaire mt-1">Vos clients commandent en ligne, vous recevez le paiement via Mobile Money.</p>
                        </div>
                    </div>

                    <div class="glass-solid p-6 flex items-start gap-5 reveal"
                         x-intersect:enter="true" x-effect="$el.classList.toggle('visible', inView)">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-succes text-white flex items-center justify-center font-bold text-lg">
                            3
                        </div>
                        <div>
                            <h3 class="font-semibold text-texte text-lg">Suivez tout</h3>
                            <p class="text-sm text-texte-secondaire mt-1">Tableau de bord simple : commandes, stock, revenus — tout est là.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Témoignage --}}
        <section class="py-16 px-5">
            <div class="max-w-lg mx-auto reveal"
                 x-intersect:enter="true" x-effect="$el.classList.toggle('visible', inView)">
                <div class="glass-solid p-6 sm:p-8 text-center">
                    <p class="text-base sm:text-lg text-texte leading-relaxed italic">
                        &laquo;&nbsp;Je ne perds plus les commandes dans mes messages WhatsApp.
                        Mes clientes commandent, je suis prévenue, c'est tout.&nbsp;&raquo;
                    </p>
                    <div class="mt-5 flex items-center justify-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-principale flex items-center justify-center text-white font-bold text-sm">
                            A
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-texte">Aïcha</p>
                            <p class="text-xs text-texte-secondaire">Vendeuse de vêtements — Cotonou</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA final --}}
        <section class="py-20 px-5">
            <div class="max-w-3xl mx-auto text-center glass-dark p-8 sm:p-12 reveal"
                 x-intersect:enter="true" x-effect="$el.classList.toggle('visible', inView)">
                <h2 class="text-2xl sm:text-3xl font-bold text-white">
                    Commence maintenant
                </h2>
                <p class="mt-3 text-white/70">
                    C'est gratuit, sans engagement.
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
                @endauth
            </div>
        </section>

        {{-- Footer minimal --}}
        <footer class="py-8 px-5 text-center">
            <p class="text-xs text-texte-secondaire">
                Vendo &middot; La plateforme de commerce social au Bénin &middot; {{ date('Y') }}
            </p>
        </footer>

        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    var splash = document.getElementById('splash');
                    if (!splash) return;
                    splash.classList.add('ferme');
                    setTimeout(function () { splash.remove(); }, 600);
                }, 2000);
            });
        </script>
    </body>
</html>
