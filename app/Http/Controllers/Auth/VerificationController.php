<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Services\User\UserServiceInterface;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificationController extends BaseController
{
    public function __construct(
        protected UserServiceInterface $userService
    ) {}

    public function verify(Request $request): JsonResponse
    {
        $user = $this->userService->findById((int) $request->route('id'));

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return $this->error('Invalid verification link.', Response::HTTP_FORBIDDEN);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->success('Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->success('Email verified successfully.');
    }

    public function resend(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->success('Email already verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->success('Verification link sent.');
    }
}
