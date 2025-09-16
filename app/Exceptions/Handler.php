<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Логирование ошибок для продакшена
            if (app()->environment('production')) {
                Log::error('Production Error', [
                    'exception' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'url' => request()->fullUrl(),
                    'user_agent' => request()->userAgent(),
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                ]);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Обработка AJAX запросов
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        // Для продакшена скрываем детали ошибок
        if (app()->environment('production')) {
            if ($e instanceof HttpException) {
                $statusCode = $e->getStatusCode();
                
                switch ($statusCode) {
                    case 403:
                        return response()->view('errors.403', [], 403);
                    case 404:
                        return response()->view('errors.404', [], 404);
                    case 500:
                        return response()->view('errors.500', [], 500);
                }
            }
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions.
     */
    protected function handleApiException($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        if ($e instanceof HttpException) {
            return response()->json([
                'message' => $e->getMessage() ?: 'Server Error',
            ], $e->getStatusCode());
        }

        // Для продакшена скрываем детали внутренних ошибок
        if (app()->environment('production')) {
            return response()->json([
                'message' => 'Internal Server Error',
            ], 500);
        }

        return response()->json([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
}
