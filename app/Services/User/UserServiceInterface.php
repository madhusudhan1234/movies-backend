<?php

namespace App\Services\User;

use App\Models\User;

interface UserServiceInterface
{
    /**
     * @param array{name: string, email: string, password: string} $data
     * @return User
     */
    public function save(array $data): User;

    public function findByEmail(string $email): ?User;

    public function findById(int $userId): User;
}
