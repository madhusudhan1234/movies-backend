<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DBTables;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Favorite extends Model
{
    protected $table = DBTables::FAVORITES;

    protected $fillable = [
        'name',
    ];
}
