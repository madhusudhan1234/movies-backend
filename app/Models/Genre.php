<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DBTables;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int             $id
 * @property string          $name
 * @property string          $label
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class Genre extends Model
{
    protected $table = DBTables::GENRES;

    protected $fillable = [
        'name',
        'label',
    ];
}
