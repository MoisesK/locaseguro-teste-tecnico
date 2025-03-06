<?php

declare(strict_types=1);

use App\Shared\Infra\Exceptions\ValidationException;
use Illuminate\Http\Request;
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
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($e instanceof ValidationException) {
                $response = [
                    'status'        => false,
                    'response'      => $e->details(),
                    'message'       => $e->getMessage(),
                    'paramError'    => true
                ];
    
                return response()->json($response, 400);
            }
    
            if ($e instanceof Throwable) {
                $response = [
                    'status'        => false,
                    'response'      => $e->getTrace(),
                    'message'       => $e->getMessage(),
                    'paramError'    => false
                ];
    
                return response()->json($response, 400);
            }
        });
    })->create();
