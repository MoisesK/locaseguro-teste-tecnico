<?php

declare(strict_types=1);

use App\Console\Commands\ConsumeQueue;
use Illuminate\Foundation\Application;
use App\Shared\Infra\Middleware\ResponseToJson;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: '/'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            ResponseToJson::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withCommands([
        ConsumeQueue::class
    ])->create();
