<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DBTables;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int             $id
 * @property string          $full_name
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class People extends Model
{
    protected $table = DBTables::PEOPLES;

    protected $fillable = [
        'full_name',
    ];
}
