<?php

use App\Http\Middleware\hakAkses as MiddlewareHakAkses;
use App\Http\Middleware\isBendahara;
use App\Http\Middleware\Verification;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['verification' => Verification::class]);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['hakAkses' => MiddlewareHakAkses::class]);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['isBendahara' => isBendahara::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
