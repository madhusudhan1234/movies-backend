<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Hash;
use Throwable;

readonly class UserService implements UserServiceInterface
{
    public function __construct(private DatabaseManager $databaseManager, private User $user) {}

    /**
     * @param  array{name: string, email: string, password: string}  $data
     *
     * @throws Throwable
     */
    public function save(array $data): User
    {
        return $this->databaseManager->transaction(function () use ($data) {
            $user = $this->user->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

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
