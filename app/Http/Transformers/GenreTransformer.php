<?php

namespace App\Http\Transformers;

use App\Models\Genre;
use League\Fractal\TransformerAbstract;

class GenreTransformer extends TransformerAbstract
{
    public function transform(Genre $genre): array
    {
        return [
            'name'  => $genre->name,
            'label' => $genre->label,
        ];
    }
}
