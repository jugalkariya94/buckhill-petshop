<?php

use App\Http\Middleware\JWTMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api_v1.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // apiPrefix: 'api/v1', // Instead of adding v1 to the api routes, we can also add it here to the api prefix
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->api(prepend: [
            JWTMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
