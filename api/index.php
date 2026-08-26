<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

header('Content-Type: text/plain; charset=utf-8');

define('LARAVEL_START', microtime(true));

echo "STEP 1: api/index.php loaded\n";
echo "PHP " . PHP_VERSION . "\n";
echo "__DIR__ = " . __DIR__ . "\n";

$path_autoload = __DIR__ . '/../vendor/autoload.php';
$path_app = __DIR__ . '/../bootstrap/app.php';
$path_maintenance = __DIR__ . '/../storage/framework/maintenance.php';

echo "autoload exists: " . (file_exists($path_autoload) ? 'YES' : 'NO') . "\n";
echo "bootstrap/app.php exists: " . (file_exists($path_app) ? 'YES' : 'NO') . "\n";

if (file_exists($path_maintenance)) {
    require $path_maintenance;
}

echo "STEP 2: requiring autoload\n";
require $path_autoload;
echo "STEP 3: autoload OK\n";

echo "STEP 4: requiring bootstrap/app.php\n";
$app = require_once $path_app;
echo "STEP 5: app created\n";

echo "STEP 6: handling request\n";
$app->handleRequest(\Illuminate\Http\Request::capture());
echo "STEP 7: done\n";
