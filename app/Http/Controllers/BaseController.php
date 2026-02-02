<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends Controller
{
    protected function success(
        string $message,
        $data = null,
        int $status = Response::HTTP_OK
    ): JsonResponse {
        $response = ['message' => $message];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return FacadesResponse::json($response, $status);
    }

    protected function error(
        string $message,
        int $status = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        return FacadesResponse::json(['message' => $message], $status);
    }
}
