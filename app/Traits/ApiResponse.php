<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
trait ApiResponse
{
    public function success(
        ?array $data = null,
        ?array $metadata = null,
        ?string $message = null,
        ?int $httpCode = Response::HTTP_OK
    ): JsonResponse {
        $response = [
            'success' => true,
        ];

        if ( !is_null($message) ) {
            $response['message'] = $message;
        }

        if ( !is_null($data) ) {
            $response['data'] = $data;
        }

        if ( !is_null($metadata) ) {
            $response['metadata'] = $metadata;
        }

        return response()->json($response, $httpCode);
    }

    public function error(
        ?string $message = null,
        int $httpCode = Response::HTTP_BAD_REQUEST,
        ?array $data = null,
        ?string $errorCode = null
    ): JsonResponse {
        $responseData = [
            'success' => false,
            'message' => $message ?? "Invalid request.",
        ];

        if ( $errorCode ) {
            $responseData['error_code'] = $errorCode;
        }

        if ( $data ) {
            $responseData['data'] = $data;
        }

        return response()->json($responseData, $httpCode);
    }
}
