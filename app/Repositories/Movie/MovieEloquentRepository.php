<?php

namespace App\Repositories\Movie;

use App\Models\Movie;
use JoBins\LaravelRepository\LaravelRepository;

/**
 *
 */
class MovieEloquentRepository extends LaravelRepository implements MovieRepository
{

    public function model(): string
    {
        return Movie::class;
    }
}
