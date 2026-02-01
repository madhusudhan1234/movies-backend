<?php

declare(strict_types=1);

namespace App\Services\Movie;

use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

interface MovieServiceInterface
{
    public function getPaginatedMovies(int $perPage = 15, string $query = ''): LengthAwarePaginator;

    public function getMovieById(int $movieId): Movie;

    public function createMovie(array $data): Movie;

    public function updateMovie(int $movieId, array $data): Movie;

    public function deleteMovie(int $movieId): bool;

    public function addToFavorites(int $userId, int $movieId): void;

    public function removeFromFavorites(int $userId, int $movieId): void;
}
