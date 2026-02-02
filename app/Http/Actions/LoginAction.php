<?php

namespace App\Http\Actions;

use App\Exceptions\LoginFailedException;
use App\Helper;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;

/**
 *
 */
class LoginAction
{
    protected string $email;
    protected string $password;
    protected string $ipAddress;

    public function __construct(
        protected readonly UserRepository $userRepository
    ) {
    }

    public function data(array $data = []): self
    {
        $this->email     = Arr::get($data, 'email');
        $this->password  = Arr::get($data, 'password');
        $this->ipAddress = Arr::get($data, 'ip');

        return $this;
    }

    /**
     * @return array
     *
     * @throws LaravelRepositoryException
     * @throws LoginFailedException
     */
    public function execute(): array
    {
        // 1. check if rete limited?
        $this->ensureIsNotRateLimited();

        // 2. login attempt
        try {
            $user = $this->attemptLogin();
        } catch (LoginFailedException $exception) {
            RateLimiter::hit($this->throttleKey());

            throw $exception;
        }

        // if login is success, clear previous rate limit counter/cache
        RateLimiter::clear($this->throttleKey());

        // 3. return token
        return [
            'token'   => $user->createToken('authtoken')->plainTextToken,
            'profile' => Helper::transform($user, new UserTransformer()),
        ];
    }

    /**
     * @throws LoginFailedException
     */
    protected function ensureIsNotRateLimited(): void
    {
        $maxAttempts = config('auth.max-login-attempt');
        if ( !RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts) ) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $message = "Too many login attempts. Please try again in {$seconds} seconds.";

        throw new LoginFailedException('RATE_LIMITED', $message);
    }

    protected function throttleKey(): string
    {
        return sprintf("%s|%s", Str::lower($this->email), $this->ipAddress);
    }

    /**
     * @return User
     * @throws LaravelRepositoryException
     * @throws LoginFailedException
     */
    protected function attemptLogin(): User
    {
        /** @var User $user */
        try {
            $user = $this->userRepository->findByField('email', $this->email);
        } catch (ModelNotFoundException $exception) {
            throw new LoginFailedException('INVALID_CREDENTIALS', 'Invalid Credentials.');
        }

        // 3. check if password matched
        if ( !Hash::check($this->password, $user->password) ) {
            throw new LoginFailedException('INVALID_CREDENTIALS', 'Invalid Credentials.');
        }

        return $user;
    }
}
