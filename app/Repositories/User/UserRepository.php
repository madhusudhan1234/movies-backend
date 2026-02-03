<?php

namespace App\Repositories\User;

use App\Models\User;
use JoBins\LaravelRepository\Contracts\RepositoryInterface;
use Throwable;

interface UserRepository extends RepositoryInterface
{
    /**
     * @return void
     *
     * @throws Throwable
     */
    public function toggleFavorites(int $movieId, User $user, bool $isFavorite = true);
}
