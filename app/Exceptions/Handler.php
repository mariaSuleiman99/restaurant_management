<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Force JSON response for API routes
        if ($request->is('api/*')) {
            // Handle Authentication Exceptions (e.g., unauthenticated users)
            if ($exception instanceof AuthenticationException or $exception instanceof RouteNotFoundException) {
                return new JsonResponse([
                    'message' => 'Unauthenticated .',
                    'error' => 'You need to log in to access this resource. for more details '.$exception->getMessage(),
                ], 401); // Return 401 Unauthorized
            }
//            // Handle RouteNotFoundException
//            if ($exception instanceof RouteNotFoundException) {
//                return new JsonResponse([
//                    'message' => 'Route not found.',
//                    'error' => $exception->getMessage(),
//                ], 404); // Return 404 Not Found
//            }

            // Handle Validation Exceptions
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return new JsonResponse([
                    'message' => 'Validation failed.',
                    'errors' => $exception->errors(),
                ], 422); // Return 422 Unprocessable Entity
            }

            // Handle Generic Exceptions
            return new JsonResponse([
                'message' => 'An error occurred.',
                'error' => $exception->getMessage(),
            ], 500); // Return 500 Internal Server Error
        }

        // Fallback to default rendering for non-API requests
        return parent::render($request, $exception);
    }
}
