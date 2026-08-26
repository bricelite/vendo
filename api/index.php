<?php

echo "Vercel boot OK\n";
echo "PHP " . PHP_VERSION . "\n";
echo "DIR: " . __DIR__ . "\n";
echo "EXISTS vendor: " . (file_exists(__DIR__.'/../vendor/autoload.php') ? 'YES' : 'NO') . "\n";
echo "EXISTS bootstrap/app: " . (file_exists(__DIR__.'/../bootstrap/app.php') ? 'YES' : 'NO') . "\n";
echo "APP_KEY set: " . (getenv('APP_KEY') ? 'YES' : 'NO') . "\n";
echo "APP_STORAGE: " . (getenv('APP_STORAGE') ?: 'NOT SET') . "\n";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET') . "\n";

flush();

try {
    require __DIR__.'/../vendor/autoload.php';
    echo "Autoload OK\n";
    flush();

    $app = require __DIR__.'/../bootstrap/app.php';
    echo "App boot OK\n";
    flush();

    $app->handleRequest(\Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "\n\nFATAL: " . $e::class . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
