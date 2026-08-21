<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Mon historique — Vendo</title>

        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-texte antialiased bg-fond-alterne">
        <header class="bg-principale">
            <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2">
                    <img src="/images/logo.png" alt="Vendo" class="h-8 w-auto">
                </a>
                <div class="flex items-center gap-3">
                    <span class="text-fond text-sm font-medium">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('client.logout') }}">
                        @csrf
                        <button type="submit" class="text-fond/80 text-sm hover:text-fond">Déconnexion</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="max-w-3xl mx-auto px-4 py-6 pb-24">
            <h1 class="text-2xl font-bold text-texte">Mes commandes</h1>

            @if ($commandes->isEmpty())
                <div class="mt-6 bg-fond rounded-2xl shadow-sm p-10 text-center">
                    <p class="text-texte-secondaire">Vous n'avez pas encore passé de commande.</p>
                </div>
            @else
                <div class="mt-4 space-y-3">
                    @foreach ($commandes as $commande)
                        <div class="bg-fond rounded-2xl p-4 shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-texte">{{ $commande->reference_courte }}</p>
                                    <p class="text-sm text-texte-secondaire truncate">
                                        {{ $commande->boutique->nom }} · {{ $commande->created_at->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-semibold text-texte">{{ number_format($commande->montant_produit, 0, ',', ' ') }} FCFA</p>
                                    <x-statut-commande :statut="$commande->statut" />
                                </div>
                            </div>

                            @if ($commande->lignes->isNotEmpty())
                                <div class="mt-3 pt-3 border-t border-fond-alterne">
                                    @foreach ($commande->lignes as $ligne)
                                        <p class="text-sm text-texte-secondaire">
                                            {{ $ligne->nom_produit }} × {{ $ligne->quantite }}
                                            — {{ number_format($ligne->prix_unitaire * $ligne->quantite, 0, ',', ' ') }} FCFA
                                        </p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </body>
</html>
