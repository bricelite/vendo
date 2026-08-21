<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Inscription client — Vendo</title>

        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-texte antialiased bg-fond-alterne">
        <div class="min-h-screen flex items-center justify-center px-4">
            <div class="w-full max-w-md">
                <div class="text-center mb-6">
                    <a href="/" class="inline-block">
                        <img src="/images/logo.png" alt="Vendo" class="h-10 w-auto mx-auto">
                    </a>
                </div>

                <div class="bg-fond rounded-2xl p-6 shadow-sm">
                    <h1 class="text-xl font-bold text-texte text-center">Créer un compte client</h1>
                    <p class="mt-1 text-sm text-texte-secondaire text-center">Suivez vos commandes et laissez des avis.</p>

                    @if ($errors->any())
                        <div class="mt-4 bg-alerte/10 text-alerte text-sm rounded-lg px-4 py-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('client.register.post') }}" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Votre nom" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="telephone" value="Numéro de téléphone" />
                            <x-text-input id="telephone" class="block mt-1 w-full" type="tel" name="telephone" :value="old('telephone')" required autocomplete="tel" placeholder="Ex. : 01 97 12 34 56" />
                            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" value="Mot de passe" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                        </div>

                        <button type="submit" class="w-full py-3 bg-principale text-white font-semibold text-sm rounded-lg">
                            Créer mon compte
                        </button>
                    </form>

                    <p class="mt-4 text-center text-sm text-texte-secondaire">
                        Déjà un compte ?
                        <a href="{{ route('client.login') }}" class="text-principale font-medium hover:underline">Se connecter</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
