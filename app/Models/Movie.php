<?php

namespace App\Models;

use App\Enums\DBTables;
use App\Enums\MovieCreditsRole;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Collection\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int             $id
 * @property string          $imdb_id
 * @property string          $title
 * @property string          $year
 * @property string          $rated
 * @property string          $released
 * @property string          $runtime
 * @property string          $plot
 * @property string          $language
 * @property string          $country
 * @property array           $awards
 * @property string          $metascore
 * @property string          $imdb_rating
 * @property string          $imdb_votes
 * @property array           $ratings
 * @property string          $type
 * @property string          $dvd
 * @property integer         $box_office_collection
 * @property string          $production
 * @property string          $website
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @property Collection      $favorites
 * @property Collection      $credits
 */
class Movie extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public const POSTER = 'poster';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'imdb_id',
        'title',
        'year',
        'rated',
        'released',
        'runtime',
        'plot',
        'language',
        'country',
        'awards',
        'metascore',
        'imdb_rating',
        'imdb_votes',
        'ratings',
        'type',
        'dvd',
        'box_office_collection',
        'production',
        'website',
    ];

    protected $casts = [
        'awards'  => 'array',
        'ratings' => 'object',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::POSTER)->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(150)->quality(50);
        $this->addMediaConversion('small')->width(300);
        $this->addMediaConversion('medium')->width(600);
        $this->addMediaConversion('large')->width(1000);
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, DBTables::FAVORITES)->withTimestamps();
    }

    public function credits(): BelongsToMany
    {
        return $this->belongsToMany(People::class, DBTables::MOVIES_CREDITS)->withPivot('role')->withTimestamps();
    }

    public function directors(): BelongsToMany
    {
        return $this->credits()->where('role', MovieCreditsRole::DIRECTOR);
    }

    public function actors(): BelongsToMany
    {
        return $this->credits()->where('role', MovieCreditsRole::ACTOR);
    }

    public function writers(): BelongsToMany
    {
        return $this->credits()->where('role', MovieCreditsRole::WRITER);
    }

    public function producers(): BelongsToMany
    {
        return $this->credits()->where('role', MovieCreditsRole::PRODUCER);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, DBTables::MOVIES_GENRE)->withTimestamps();
    }
}
