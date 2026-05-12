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
        $middleware->alias([
            'enrollment' => \App\Middleware\EnrollmentMiddleware::class,
            'api.user.auth' => \App\Middleware\ApiUserAuthenticated::class,
        ]);
        $middleware->prepend(\App\Middleware\EncryptCookies::class);
        $middleware->encryptCookies(except: [
            'guest_user_id',
            'auth_api_token',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
