<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\FacilitatorMiddleware;
use App\Http\Middleware\ComHeadMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SubAdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'facilitatorMiddleware' => FacilitatorMiddleware::class,
            'comHeadMiddleware' => ComHeadMiddleware::class,
            'adminMiddleware' => AdminMiddleware::class,
            'subAdminMiddleware' => SubAdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
