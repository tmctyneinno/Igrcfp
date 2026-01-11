<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware aliases
        $middleware->alias([
            'admin.role' => App\Http\Middleware\CheckAdminRole::class,
            'auth.admin' => App\Http\Middleware\AuthenticateAdmin::class,
            'guest:admin' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
        
        // Admin middleware group (more streamlined)
        $middleware->group('admin', [
            'auth:admin',
            'web', // This includes all web middleware
        ]);
        
        // Web middleware with Inertia (as you have)
        $middleware->web(append: [
            App\Http\Middleware\HandleInertiaRequests::class,
            Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling if needed
    })->create();