<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Connexion client — Vendo</title>

        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-texte antialiased">
        <div class="min-h-screen flex items-center justify-center px-4">
            <div class="w-full max-w-md">
                <div class="text-center mb-6">
                    <a href="/" class="text-3xl font-bold text-principale">Vendo</a>
                </div>

                <div class="glass-solid rounded-2xl p-6 shadow-sm">
                    <h1 class="text-xl font-bold text-texte text-center">Connexion client</h1>
                    <p class="mt-1 text-sm text-texte-secondaire text-center">Retrouvez vos commandes et votre historique.</p>

                    @if ($errors->any())
                        <div class="mt-4 bg-alerte/10 text-alerte text-sm rounded-xl px-4 py-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('client.login.post') }}" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="telephone" value="Numéro de téléphone" />
                            <x-text-input id="telephone" class="block mt-1 w-full rounded-xl" type="tel" name="telephone" :value="old('telephone')" required autofocus autocomplete="tel" placeholder="Ex. : 01 97 12 34 56" />
                            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" value="Mot de passe" />
                            <x-text-input id="password" class="block mt-1 w-full rounded-xl" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <button type="submit" class="w-full py-3 bg-principale text-white font-semibold text-sm rounded-xl hover:bg-principale/90 transition">
                            Se connecter
                        </button>
                    </form>

                    <p class="mt-4 text-center text-sm text-texte-secondaire">
                        Pas encore de compte ?
                        <a href="{{ route('client.register') }}" class="text-principale font-medium hover:underline">Créer un compte</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
