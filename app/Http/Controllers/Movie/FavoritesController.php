<?php

declare(strict_types=1);

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FavoritesController extends Controller
{
    public function addToFavorites(Movie $movie): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var User $user */
        $user = auth()->user();

        // Check if already favorited
        if ($user->favoriteMovies()->where('movie_id', $movie->id)->exists()) {
            return response()->json([
                'message' => 'Movie is already in favorites',
            ], Response::HTTP_CONFLICT);
        }

        $user->favoriteMovies()->attach($movie->id);

        return response()->json([
            'message' => 'Movie added to favorites',
            'data' => new MovieResource($movie),
        ]);
    }

    public function removeFromFavorites(Movie $movie): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var User $user */
        $user = auth()->user();

        $user->favoriteMovies()->detach($movie->id);

        return response()->json([
            'message' => 'Movie removed from favorites',
        ]);
    }
}
