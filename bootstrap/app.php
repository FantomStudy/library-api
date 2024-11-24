<?php

use App\Http\Middleware\AbilityMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'abilities' => AbilityMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->render(function (AuthorizationException $e, $request) {
            if($request->is('api/*')) {
                return response()->json([
                    "success" => false,
                    'message' => $e->getMessage(),
                ], 401);
            }
        });

        $exceptions->render(function (AuthenticationException $e, $request) {
            if($request->is('api/*')) {
                return response()->json([
                    "success" => false,
                    'message' => $e->getMessage(),
                ], 403);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if($request->is('api/*')) {
                return response()->json([
                    "success" => false,
                    'message' => $e->getMessage(),
                ], 404);
            }
        });

        $exceptions->render(function (ValidationException $e, $request) {
            if($request->is('api/*')) {
                return response()->json([
                    "success" => false,
                    'message' => "Validation error",
                    'errors' => $e->errors(),
                ], 422);
            }
        });
    })->create();
