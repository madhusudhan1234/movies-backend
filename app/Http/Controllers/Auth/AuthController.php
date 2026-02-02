<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    public function __construct(
        protected UserServiceInterface $userService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->save($request->validated());

        $token = $user->createtoken('authToken')->plainTextToken;

        return $this->success('Registered successfully.', [
            'user' => new UserResource($user),
            'token' => $token,
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->userService->findByEmail($request->email);

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error('Invalid Credentials', Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('authtoken')->plainTextToken;

        return $this->success('Logged in successfully.', [
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logged out successfully.');
    }
}
