<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use JoBins\LaravelRepository\Filters\Filterable;

class MovieFilter extends Filterable
{
    public function qFilter(Builder $model, ?string $search): Builder
    {
        return $model->where('title', 'ilike', '%'.$search.'%');
    }

    public function favoriteFilter(Builder $model, ?int $userId): Builder
    {
        if (! $userId) {
            return $model;
        }

        return $model->whereHas('favorites', fn ($q) => $q->where('user_id', $userId));
    }
}
