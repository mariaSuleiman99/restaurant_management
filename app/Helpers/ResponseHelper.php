<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

class ResponseHelper
{
    /**
     * Success Response
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(string $message = "Success", $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Error Response
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed|null $errors
     * @return JsonResponse
     */
    public static function error(string $message = "Error", int $statusCode = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Handle Validation Errors
     *
     * @param \Illuminate\Support\MessageBag $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function validationError(MessageBag $errors, int $statusCode = 422): JsonResponse
    {
        return self::error("Validation failed.", $statusCode, $errors->toArray());
    }

}
