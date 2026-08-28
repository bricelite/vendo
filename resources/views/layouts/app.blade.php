<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-screen overflow-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($header) ? $header.' — ' : '' }}{{ config('app.name', 'Vendo') }}</title>

        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-texte antialiased h-screen overflow-hidden">
        <div class="flex h-full flex-col">
            {{-- Barre haute : mobile uniquement --}}
            @include('layouts.navigation')

            {{-- Contenu principal : seule zone qui défile, le cadre de la page ne défile pas
                 (sauf dashboard, qui est verrouillé sans défilement) --}}
            <main class="flex-1 min-h-0 w-full max-w-5xl mx-auto px-4 pt-6 sm:pt-8 pb-28 md:pb-6 md:ml-60 {{ ($sansDefilement ?? false) ? 'overflow-hidden' : 'overflow-y-auto' }}">
                {{ $slot }}
            </main>
        </div>

        {{-- Navigation basse : mobile uniquement --}}
        @include('layouts.bottom-nav')

        {{-- Toasts : messages de confirmation / erreur --}}
        <div id="vendo-toasts" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-sm space-y-2 pointer-events-none"></div>

        @if (session('succes'))
            <div class="vendo-toast vendo-toast-succes" data-vendo-toast>
                <span>{{ session('succes') }}</span>
                <button type="button" data-vendo-toast-fermer>×</button>
            </div>
        @endif

        @if (session('erreur'))
            <div class="vendo-toast vendo-toast-erreur" data-vendo-toast>
                <span>{{ session('erreur') }}</span>
                <button type="button" data-vendo-toast-fermer>×</button>
            </div>
        @endif
    </body>
</html>
