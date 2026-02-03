<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\LoginFailedException;
use App\Http\Actions\LoginAction;
use App\Http\Actions\UserRegisterAction;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    /**
     * @param RegisterRequest    $request
     * @param UserRegisterAction $action
     *
     * @return JsonResponse
     * @throws LaravelRepositoryException
     */
    public function register(RegisterRequest $request, UserRegisterAction $action): JsonResponse
    {
        $user = $action->execute($request->validated());

        return $this->success($user, httpCode: Response::HTTP_CREATED);
    }

    /**
     * @throws LaravelRepositoryException
     */
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        try {
            $user = $action->data([
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
                'ip'       => $request->ip(),
            ])->execute();
        } catch (LoginFailedException $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_UNAUTHORIZED, [
                'error_type' => $exception->getErrorType(),
            ]);
        }

        return $this->success($user, message: 'Logged in successfully.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(message: 'Logged out successfully.');
    }
}
