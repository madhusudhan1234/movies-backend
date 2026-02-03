<?php

namespace App\Repositories\User;

use App\Models\Movie;
use App\Models\User;
use App\Repositories\Movie\MovieRepository;
use Illuminate\Support\Facades\DB;
use JoBins\LaravelRepository\LaravelRepository;
use Throwable;

/**
 *
 */
class UserEloquentRepository extends LaravelRepository implements UserRepository
{
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param int  $movieId
     * @param User $user
     * @param bool $isFavorite
     *
     * @return void
     * @throws Throwable
     */
    public function toggleFavorites(int $movieId, User $user, bool $isFavorite = true)
    {
        DB::transaction(function () use ($movieId, $user, $isFavorite) {
            // check if movie exists
            /** @var Movie $movie */
            $movie = app(MovieRepository::class)->find($movieId);

            // check if already favorites
            if ( $isFavorite ) {
                if ( $user->favoriteMovies()->where('movie_id', $movie->id)->exists() ) {
                    return;
                }
            }

            // do action
            if ( $isFavorite ) {
                $user->favoriteMovies()->attach($movie->id);
            } else {
                $user->favoriteMovies()->detach($movie->id);
            }
        });
    }
}
