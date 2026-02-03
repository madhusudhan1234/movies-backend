<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LoginFailedException extends Exception
{
    use ApiResponse;

    protected string $errorType;

    public function __construct(string $errorType, string $message = '')
    {
        parent::__construct($message);

        $this->errorType = $errorType;
    }

    public function getErrorType(): string
    {
        return $this->errorType;
    }

    public function render(): JsonResponse
    {
        return $this->error(
            $this->getMessage(),
            Response::HTTP_UNAUTHORIZED,
            [
                'error_type' => $this->getErrorType(),
            ]
        );
    }
}
