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
}
