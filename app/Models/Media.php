<?php

namespace App\Models;

use App\Enums\DBTables;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 *
 */
class Media extends BaseMedia
{
    protected $table = DBTables::MEDIAS;
}
