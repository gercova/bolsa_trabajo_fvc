<?php

use App\Http\Middleware\CountWebsiteVisits;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            CountWebsiteVisits::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            $status = $response->getStatusCode();
            $errorStatuses = [400, 401, 402, 403, 404, 419, 429, 500, 503];

            // Don't intercept API or raw JSON requests unless it's explicitly an Inertia request
            if ($request->expectsJson() && ! $request->header('X-Inertia')) {
                return $response;
            }

            // In local environment with debug mode enabled, allow standard debug screen for 500 when not an Inertia request
            if ($status === 500 && config('app.debug') && ! $request->header('X-Inertia') && app()->environment('local')) {
                return $response;
            }

            if (in_array($status, $errorStatuses, true)) {
                Inertia::setRootView('inertia-app');

                return Inertia::render('Error', [
                    'status' => $status,
                ])
                    ->toResponse($request)
                    ->setStatusCode($status);
            }

            return $response;
        });
    })->create();
