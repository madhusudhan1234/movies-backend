<?php

namespace App\Services;

use App\Models\User;

interface UserServiceInterface
{
    public function save(array $data): User;

    public function findByEmail(string $email): ?User;

    public function findById(int $userId): User;
}
