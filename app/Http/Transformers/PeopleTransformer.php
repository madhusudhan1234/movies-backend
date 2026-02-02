<?php

namespace App\Http\Transformers;

use App\Models\People;
use League\Fractal\TransformerAbstract;

class PeopleTransformer extends TransformerAbstract
{
    public function transform(People $people): array
    {
        return [
            'full_name' => $people->full_name,
        ];
    }
}
