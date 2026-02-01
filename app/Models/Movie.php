<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int $id Movie ID (Primary Key)
 * @property string|null $imdb_id IMDB identifier (unique)
 * @property string $title Movie title (required)
 * @property int|null $year Release year
 * @property string|null $rated Rating classification (G, PG, PG-13, R, NC-17, etc.)
 * @property string|null $released Release date (YYYY-MM-DD)
 * @property string|null $runtime Runtime duration (e.g., "148 min")
 * @property string|null $genre Comma-separated genres (e.g., "Action, Sci-Fi, Thriller")
 * @property string|null $director Director name(s)
 * @property string|null $writer Writer/screenwriter name(s)
 * @property string|null $actors Actor name(s) (comma-separated)
 * @property string|null $plot Movie plot summary/description
 * @property string|null $language Languages used in movie
 * @property string|null $country Production country/countries
 * @property string|null $awards Awards and nominations text
 * @property string|null $poster URL to movie poster image
 * @property float|null $metascore Metascore rating (0-100)
 * @property float|null $imdb_rating IMDB rating (0-10)
 * @property string|null $imdb_votes Number of IMDB votes
 * @property string|null $type Movie type (movie, series, episode, etc.)
 * @property string|null $dvd DVD release date (YYYY-MM-DD)
 * @property string|null $box_office Box office earnings
 * @property string|null $production Production company name
 * @property string|null $website Official movie website URL
 * @property \Carbon\Carbon $created_at Timestamp when record was created
 * @property \Carbon\Carbon $updated_at Timestamp when record was last updated
 */
class Movie extends Model
{
    use HasFactory;

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
        'genre',
        'director',
        'writer',
        'actors',
        'plot',
        'language',
        'country',
        'awards',
        'poster',
        'metascore',
        'imdb_rating',
        'imdb_votes',
        'type',
        'dvd',
        'box_office',
        'production',
        'website',
    ];

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
