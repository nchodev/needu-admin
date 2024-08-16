<?php

use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\LocalizationApiMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        using: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/api/v1/api.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            Localization::class,
             AdminAuth::class,
        ]);
        $middleware->api(prepend: [
            LocalizationApiMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
