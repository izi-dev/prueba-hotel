<?php

declare(strict_types=1);

/**
 * Bootstrap de la aplicación Laravel.
 *
 * Configura enrutamiento (web, API, consola y health check), middleware CORS
 * para la API y el manejo centralizado de excepciones de dominio en JSON.
 */
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Src\Domain\Exceptions\DomainException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->api(prepend: [
            HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Las peticiones bajo /api/* siempre reciben respuestas JSON, incluso en errores.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Traduce excepciones de dominio a respuestas HTTP con el código definido en cada una.
        $exceptions->render(function (DomainException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->statusCode());
            }
        });
    })->create();
