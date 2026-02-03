<?php

declare(strict_types=1);

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\BaseController;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FavoritesController extends BaseController
{
    public function addToFavorites(Movie $movie): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->favoriteMovies()->where('movie_id', $movie->id)->exists()) {
            return $this->error('Movie is already in favorites.', Response::HTTP_CONFLICT);
        }

        $user->favoriteMovies()->attach($movie->id);

        return $this->success('Movie added to favorites.', $movie);
    }

    public function removeFromFavorites(Movie $movie): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->favoriteMovies()->detach($movie->id);

        return $this->success('Movie removed from favorites.');
    }
}
