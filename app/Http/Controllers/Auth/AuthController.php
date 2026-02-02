<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\LoginFailedException;
use App\Http\Actions\LoginAction;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\User\UserServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    public function __construct(
        protected UserServiceInterface $userService,
        protected readonly UserRepository $userRepository
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->save($request->validated());

        $token = $user->createtoken('authToken')->plainTextToken;

        return $this->success('Registered successfully.', [
            'user'  => new UserResource($user),
            'token' => $token,
        ], Response::HTTP_CREATED);
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

        return $this->success('Logged in successfully.', $user);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logged out successfully.');
    }
}
