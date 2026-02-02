<?php

namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

/**
 *
 */
class UserTransformer extends TransformerAbstract
{
    public function transform(User $user): array
    {
        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ];
    }
}
