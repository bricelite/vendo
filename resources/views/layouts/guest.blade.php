<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Vendo') }}</title>

        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-texte antialiased">
        <div id="vendo-toasts" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-sm space-y-2 pointer-events-none"></div>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-fond-alterne">
            <div class="mb-6">
                <a href="/" class="inline-block overflow-hidden rounded-full" style="width:72px;height:72px">
                    <img src="/images/logo.png" alt="Vendo" style="width:100%;height:100%;object-fit:cover">
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-6 bg-fond shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
