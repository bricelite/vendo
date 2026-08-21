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
                background-color: #1A4883;
                transition: opacity .5s ease, visibility .5s ease;
            }
            #splash.ferme {
                opacity: 0;
                visibility: hidden;
            }
            #splash img {
                opacity: 0;
                transform: scale(.9);
                animation: splash-logo .6s ease forwards;
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
                background: #F2801F;
                animation: splash-progress 2s ease forwards;
            }
            @keyframes splash-logo {
                to { opacity: 1; transform: scale(1); }
            }
            @keyframes splash-progress {
                to { width: 100%; }
            }
        </style>
    </head>
    <body class="font-sans text-texte antialiased bg-fond">

        <div id="splash" aria-hidden="true">
            <div style="width:128px;max-width:50vw;aspect-ratio:1;overflow:hidden;display:block;border-radius:50%">
                <img src="/images/logo.png" alt="" style="width:100%;height:100%;object-fit:cover">
            </div>
            <div class="barre"><span></span></div>
        </div>

        <header class="bg-principale">
            <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="/" class="overflow-hidden rounded-full" style="width:40px;height:40px">
                    <img src="/images/logo.png" alt="Vendo" style="width:100%;height:100%;object-fit:cover">
                </a>
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-fond hover:text-accent">
                            Tableau de bord
                        </a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-sm font-medium text-fond hover:text-accent">
                                Se connecter
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="text-sm font-semibold bg-accent text-white px-4 py-2 rounded-md">
                                Créer ma boutique
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main>
            <section class="bg-principale"
                     style="background-image: url('/images/accueil.jpg'); background-size: cover; background-position: center;">
                <div class="max-w-3xl mx-auto px-4 py-14 sm:py-24">
                    <div class="max-w-xl mx-auto text-center rounded-2xl px-5 py-8 sm:px-10 sm:py-12 shadow-lg"
                         style="background: rgba(26, 72, 131, 0.85);">
                        <h1 class="text-2xl sm:text-4xl font-bold text-fond leading-snug"
                            x-data="{ mots: ['WhatsApp', 'Instagram', 'Facebook'], index: 0 }"
                            x-init="setInterval(() => index = (index + 1) % mots.length, 2200)">
                            Ta boutique <span class="text-accent" x-text="mots[index]"></span>,<br>enfin organisée.
                        </h1>
                        <p class="mt-4 text-sm sm:text-base text-fond leading-relaxed">
                            Tu vends déjà sur WhatsApp ou Instagram ? Vendo te donne une boutique en ligne,
                            un suivi des commandes et un tableau de bord simple — sans remplacer tes réseaux.
                        </p>

                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="mt-8 inline-block w-full sm:w-auto px-10 py-4 bg-accent text-white font-bold text-base rounded-lg shadow-lg">
                                Ouvrir mon tableau de bord
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                               class="mt-8 inline-block w-full sm:w-auto px-10 py-4 bg-accent text-white font-bold text-base rounded-lg shadow-lg">
                                Créer ma boutique
                            </a>
                            <p class="mt-4 text-sm text-fond">
                                Déjà une boutique ?
                                <a href="{{ route('login') }}" class="font-semibold text-accent underline underline-offset-2">
                                    Se connecter
                                </a>
                            </p>
                            <div class="mt-8 grid grid-cols-3 border-t border-white/20 pt-6">
                                <div class="text-center px-2">
                                    <p class="text-xl sm:text-2xl font-bold text-fond">0 FCFA</p>
                                    <p class="text-xs text-fond/70">pour démarrer</p>
                                </div>
                                <div class="text-center px-2 border-x border-white/20">
                                    <p class="text-xl sm:text-2xl font-bold text-fond">100%</p>
                                    <p class="text-xs text-fond/70">Mobile Money</p>
                                </div>
                                <div class="text-center px-2">
                                    <p class="text-xl sm:text-2xl font-bold text-fond">5 min</p>
                                    <p class="text-xs text-fond/70">pour démarrer</p>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </section>

            <section class="max-w-3xl mx-auto px-4 py-10">
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm">
                    <p class="text-base sm:text-lg text-texte leading-relaxed italic">
                        &laquo;&nbsp;Je ne perds plus les commandes dans mes messages WhatsApp.
                        Mes clientes commandent, je suis prévenue, c'est tout.&nbsp;&raquo;
                    </p>
                    <div class="mt-5 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-principale flex items-center justify-center text-white font-bold text-sm">
                            A
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-texte">Aïcha</p>
                            <p class="text-xs text-texte-secondaire">Vendeuse de vêtements — Cotonou</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="max-w-3xl mx-auto px-4 py-12">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-fond-alterne rounded-2xl p-5 text-center">
                        <div class="mx-auto h-14 w-14 rounded-full bg-principale flex items-center justify-center">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                            </svg>
                        </div>
                        <p class="mt-3 text-base font-semibold text-texte">Publiez</p>
                        <p class="mt-1 text-xs text-texte-secondaire">Ajoutez vos articles avec prix et stock.</p>
                    </div>
                    <div class="bg-fond-alterne rounded-2xl p-5 text-center">
                        <div class="mx-auto h-14 w-14 rounded-full bg-principale flex items-center justify-center">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                        <p class="mt-3 text-base font-semibold text-texte">Encaissez</p>
                        <p class="mt-1 text-xs text-texte-secondaire">Vos clients commandent, vous encaissez.</p>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-fond-alterne">
            <div class="max-w-3xl mx-auto px-4 py-6 text-center text-xs text-texte-secondaire">
                Vendo · la plateforme de commerce social au Bénin
            </div>
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
