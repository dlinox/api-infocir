<?php

use App\Common\Exceptions\ApiException;
use App\Common\Exceptions\PermissionDeniedException;
use App\Common\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \App\Common\Http\Middleware\ForceJsonResponse::class,
        ]);

        $middleware->alias([
            'permission' => \App\Common\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e) {
            return ApiResponse::unauthorized($e->getMessage());
        });

        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::validationError(
                $e->errors(),
                $e->getMessage()
            );
        });

        $exceptions->render(function (ApiException $e) {
            $code = $e->getCode() ?: 400;
            return ApiResponse::error($e->getMessage(), null, $code);
        });

        $exceptions->render(function (PermissionDeniedException $e) {
            return ApiResponse::error($e->getMessage(), null, 403);
        });

        $exceptions->render(function (QueryException $e) {
            if (app()->hasDebugModeEnabled()) {
                return ApiResponse::serverError($e->getMessage(), [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }

            return ApiResponse::serverError('Error en la base de datos. Verifique los datos enviados.', null, 500);
        });

        $exceptions->render(function (Throwable $e) {
            if (app()->hasDebugModeEnabled()) {
                return ApiResponse::serverError($e->getMessage(), [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => collect($e->getTrace())->take(5)->toArray(),
                ]);
            }

            return ApiResponse::serverError('Internal server error');
        });
    })
    ->create();
