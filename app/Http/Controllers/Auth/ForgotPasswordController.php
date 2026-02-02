<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\ResetLinkRequest;
use App\Http\Requests\Auth\ResetRequest;
use App\Repositories\User\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
/**
 *
 */
class ForgotPasswordController extends BaseController
{
    public function __construct(
        protected readonly UserRepository $userRepository
    ) {
    }

    public function sendResetLink(ResetLinkRequest $request): JsonResponse
    {
        $status = Password::sendResetLink($request->only('email'));

        if ( $status === Password::RESET_LINK_SENT ) {
            return $this->success(__($status));
        }

        return $this->error(__($status));
    }

    public function reset(ResetRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $this->userRepository->update([
                    'password'       => $password,
                    'remember_token' => Str::random(60),
                ], $user->id);

                event(new PasswordReset($user));
            }
        );

        if ( $status === Password::PASSWORD_RESET ) {
            return $this->success(__($status));
        }

        return $this->error(__($status));
    }
}
