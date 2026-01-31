<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function save(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->save();

            event(new Registered($user));

            return $user;
        });
    }

    public function findByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $userId): User
    {
        return User::findOrFail($userId);
    }
}
