<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ModuleException $e, $request) {
            // Log the exception
            $e->log();

            // Return JSON response for API requests
            if ($request->expectsJson()) {
                return response()->json(
                    data: $e->toArray(),
                    status: $e->getHttpStatusCode()
                );
            }

            // Return error page for web requests
            return response()->view(
                view: 'errors.exception',
                data: [
                    'exception' => $e,
                    'message' => $e->getMessage(),
                    'model' => $e->getModel(),
                    'context' => $e->getContext(),
                ],
                status: $e->getHttpStatusCode()
            );
        });

        $this->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => [
                        'message' => 'Model not found',
                        'code' => 404,
                    ],
                ], 404);
            }
        });

        $this->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => [
                        'message' => 'Validation failed',
                        'code' => 422,
                        'errors' => $e->errors(),
                    ],
                ], 422);
            }
        });
    }
}
