<?php

namespace App\Http\Actions;

use App\Helper;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use App\Repositories\User\UserRepository;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;

/**
 *
 */
class UserRegisterAction
{
    public function __construct(
        protected readonly UserRepository $userRepository
    ) {
    }

    /**
     * @throws LaravelRepositoryException
     */
    public function execute(array $data): array
    {
        /** @var User $user */
        $user = $this->userRepository->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        return [
            'token'   => $user->createToken('authtoken')->plainTextToken,
            'profile' => Helper::transform($user, new UserTransformer()),
        ];
    }
}
