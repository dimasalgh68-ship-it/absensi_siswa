<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
// [ADJUST PATH IF NEEDED] 
// We recommend: __DIR__.'/../laravel/storage/...' (Secure, outside public_html)
// Your snippet: __DIR__.'/laravel/storage/...' (Inside public_html)
$path_to_laravel = __DIR__.'/../laravel'; // Change to '/laravel' if you MUST put it inside.

if (file_exists($maintenance = $path_to_laravel.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $path_to_laravel.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once $path_to_laravel.'/bootstrap/app.php';

// [CRITICAL] Set the public path to the current directory
// This fixes "css/js error" by telling Laravel that 'public' is here, not in /laravel/public
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
