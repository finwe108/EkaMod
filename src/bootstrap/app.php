<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            return route('login', ['expired' => 1]);
        });

        $middleware->redirectUsersTo('/');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return redirect()->guest(route('login', ['expired' => 1]));
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (config('app.debug')) {
                return null;
            }

            $status = method_exists($e, 'getStatusCode')
                ? $e->getStatusCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($status === Response::HTTP_NOT_FOUND) {
                return response()->view('errors.404', [], 404);
            }

            if ($status === Response::HTTP_FORBIDDEN) {
                return response()->view('errors.403', [], 403);
            }

            $errorId = strtoupper(substr(md5(now() . $e->getMessage()), 0, 8));

            Log::error('Public error ID: ' . $errorId, [
                'exception' => $e,
                'url' => $request->fullUrl(),
                'user_id' => optional($request->user())->id,
            ]);

            return response()->view('errors.500', [
                'errorId' => $errorId,
            ], 500);
        });
    })
    ->create();