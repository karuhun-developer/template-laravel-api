<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api/v1.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
        health: '/up',
    )


    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api('throttle:60,1');
        $middleware->redirectGuestsTo('/login');

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'auth.api-key' => \App\Http\Middleware\AuthApiKey::class,
            'validate-role-permission' => \App\Http\Middleware\ValidateRolePermission::class,
        ]);
    })
    ->withEvents(discover: [
        //
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->stopIgnoring(AuthenticationException::class);
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'code' => 401,
                    'message' => $exception->getMessage(),
                ], 401);
            }
        });
    })->create();
