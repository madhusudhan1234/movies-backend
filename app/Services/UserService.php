<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Hash;

readonly class UserService implements UserServiceInterface
{
    public function __construct(private DatabaseManager $databaseManager, private User $user) {}

    /**
     * @var array{id: string, name: string, age: int}
     *
     * @throws \Throwable
     */
    public function save(array $data): User
    {
        return $this->databaseManager->transaction(function () use ($data) {
            $user = new User; // Why user ko instance na leko hamle?
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']); // laravel before create hook => mutator
            $user->save();
            // UserResource::create

            event(new Registered($user));

            return $user;
        }, 2);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $userId): User
    {
        return User::findOrFail($userId);
    }
}
