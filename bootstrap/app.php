<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
            'teacher' => \App\Http\Middleware\EnsureUserIsTeacher::class,
            'student' => \App\Http\Middleware\EnsureUserIsStudent::class,
        ]);
        
        // Trust proxies for ngrok/reverse proxy support
        $middleware->trustProxies(at: '*');
        
        // Add dynamic URL middleware for ngrok/proxy support
        $middleware->web(append: [
            \App\Http\Middleware\SetDynamicAppUrl::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()->route('login')->with('status', 'Sesi Anda telah berakhir. Silakan login kembali.');
        });
    })->create();
