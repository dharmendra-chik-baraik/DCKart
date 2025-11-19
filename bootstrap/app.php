<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Register additional route files
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
                
            Route::middleware('web')
                ->group(base_path('routes/customer.php'));
                
            Route::middleware('web')
                ->group(base_path('routes/vendor.php'));
                
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register your custom middleware aliases
        $middleware->alias([
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'vendor' => \App\Http\Middleware\VendorMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();