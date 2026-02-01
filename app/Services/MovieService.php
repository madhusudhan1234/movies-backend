<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class MovieService implements MovieServiceInterface
{
    public function __construct(
        protected Movie $movie
    ) {}

    public function getPaginatedMovies(int $perPage = 15, string $query = ''): LengthAwarePaginator
    {
        return $this->movie->query()
            ->when($query, function ($builder, $searchQuery) {
                $builder->where(function ($sub) use ($searchQuery) {
                    $sub->where('title', 'like', '%'.$searchQuery.'%')
                        ->orWhere('director', 'like', '%'.$searchQuery.'%')
                        ->orWhere('actors', 'like', '%'.$searchQuery.'%');
                });
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getMovieById(int $movieId): Movie
    {
        return $this->movie->findOrFail($movieId);
    }

    public function createMovie(array $data): Movie
    {
        return $this->movie->create($data);
    }

    public function updateMovie(int $movieId, array $data): Movie
    {
        $movie = $this->getMovieById($movieId);
        $movie->update($data);

        return $movie;
    }

    public function deleteMovie(int $movieId): bool
    {
        return $this->getMovieById($movieId)->delete();
    }

    public function addToFavorites(int $userId, int $movieId): void
    {
        $movie = $this->getMovieById($movieId);
        $movie->favoritedBy()->attach($userId);
    }

    public function removeFromFavorites(int $userId, int $movieId): void
    {
        $movie = $this->getMovieById($movieId);
        $movie->favoritedBy()->detach($userId);
    }
}
