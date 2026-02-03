<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class VerificationController extends Controller
{
    public function __construct(
        protected readonly UserRepository $userRepository
    ) {
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws LaravelRepositoryException
     */
    public function verify(int $id, string $hash): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if ( !hash_equals($hash, sha1($user->getEmailForVerification())) ) {
            return $this->error('Invalid verification link.', Response::HTTP_FORBIDDEN);
        }

        if ( $user->hasVerifiedEmail() ) {
            return $this->success(message: 'Email already verified.');
        }

        if ( $user->markEmailAsVerified() ) {
            event(new Verified($user));
        }

        return $this->success(message: 'Email verified successfully.');
    }

    public function resend(Request $request): JsonResponse
    {
        if ( $request->user()->hasVerifiedEmail() ) {
            return $this->success(message: 'Email already verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->success(message: 'Verification link sent.');
    }
}
