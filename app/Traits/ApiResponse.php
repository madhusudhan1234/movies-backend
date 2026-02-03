<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
trait ApiResponse
{
    /**
     * @param string $message
     * @param        $data
     * @param int    $status
     *
     * @return JsonResponse
     */
    protected function success(
        string $message,
        $data = null,
        int $status = Response::HTTP_OK
    ): JsonResponse {
        if ( $data !== null ) {
            $response = $data;
        }

        $response['message'] = $message;

        return FacadesResponse::json($response, $status);
    }

    /**
     * @param string $message
     * @param int    $status
     *
     * @return JsonResponse
     */
    protected function error(
        string $message,
        int $status = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        return FacadesResponse::json(['message' => $message], $status);
    }
}
