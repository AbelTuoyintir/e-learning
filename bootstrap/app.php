<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         // 👇 Global middleware (applies to all routes)
    $middleware->append(\App\Http\Middleware\ActivityLogger::class);

    // Optionally also append to specific groups
    $middleware->web(append: [
        \App\Http\Middleware\ActivityLogger::class,
    ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
