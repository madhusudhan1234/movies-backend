<?php

namespace App\Http\Transformers;

use App\Models\Movie;
use Chotkari\Core\Domain\News\Models\News;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class MovieTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = ['genres', 'directors', 'actors', 'producers', 'poster'];

    public function transform(Movie $movie): array
    {
        return [
            'id'                    => $movie->id,
            'imdb_id'               => $movie->imdb_id,
            'title'                 => $movie->title,
            'year'                  => $movie->year,
            'rated'                 => $movie->rated,
            'released'              => $movie->released,
            'runtime'               => $movie->runtime,
            'plot'                  => $movie->plot,
            'language'              => $movie->language,
            'country'               => $movie->country,
            'awards'                => $movie->awards,
            'metascore'             => $movie->metascore,
            'imdb_rating'           => $movie->imdb_rating,
            'imdb_votes'            => $movie->imdb_votes,
            'ratings'               => $movie->ratings,
            'type'                  => $movie->type,
            'dvd'                   => $movie->dvd,
            'box_office_collection' => $movie->box_office_collection,
            'production'            => $movie->production,
            'website'               => $movie->website,
            'created_at'            => $movie->created_at,
            'updated_at'            => $movie->updated_at,
        ];
    }

    public function includeGenres(Movie $movie): Collection
    {
        return $this->collection($movie->genres, new GenreTransformer());
    }

    public function includeDirectors(Movie $movie): Collection
    {
        return $this->collection($movie->directors, new PeopleTransformer());
    }

    public function includeActors(Movie $movie): Collection
    {
        return $this->collection($movie->actors, new PeopleTransformer());
    }

    public function includeProducers(Movie $movie): Collection
    {
        return $this->collection($movie->producers, new PeopleTransformer());
    }

    public function includePoster(Movie $movie): ?Item
    {
        $poster = $movie->getFirstMedia(Movie::POSTER);

        if ( !$poster ) {
            return null;
        }

        return $this->item($poster, new MediaTransformer());
    }
}
