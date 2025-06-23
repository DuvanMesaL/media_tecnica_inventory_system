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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
            'logistics' => \App\Http\Middleware\LogisticsMiddleware::class,
            'profile.complete' => \App\Http\Middleware\EnsureProfileCompleted::class,
        ]);

        // Apply profile completion check to web routes
        $middleware->web(append: [
            \App\Http\Middleware\EnsureProfileCompleted::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
