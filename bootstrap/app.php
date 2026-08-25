<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Sur Vercel le disque est en lecture seule : on redirige le stockage
// vers /tmp quand la variable APP_STORAGE est définie dans l'environnement.
if (env('APP_STORAGE')) {
    $app->useStoragePath(env('APP_STORAGE'));

    foreach (['framework/views', 'framework/cache/data', 'app/private/uploads', 'logs'] as $dossier) {
        @mkdir(storage_path($dossier), 0775, true);
    }
}

return $app;
