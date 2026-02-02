<?php

namespace App\Repositories\User;

use App\Models\User;
use JoBins\LaravelRepository\LaravelRepository;

/**
 *
 */
class UserEloquentRepository extends LaravelRepository implements UserRepository
{
    public function model(): string
    {
        return User::class;
    }
}
