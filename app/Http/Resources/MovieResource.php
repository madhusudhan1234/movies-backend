<?php

namespace App\Http\Resources;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /***
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Movie $this */
        return [
            'id' => $this->id,
            'imdb_id' => $this->imdb_id,
            'title' => $this->title,
            'year' => $this->year,
            'rated' => $this->rated,
            'released' => $this->released,
            'runtime' => $this->runtime,
            'genre' => $this->genre,
            'director' => $this->director,
            'writer' => $this->writer,
            'actors' => $this->actors,
            'plot' => $this->plot,
            'language' => $this->language,
            'country' => $this->country,
            'awards' => $this->awards,
            'poster' => $this->poster,
            'metascore' => $this->metascore,
            'imdb_rating' => $this->imdb_rating,
            'imdb_votes' => $this->imdb_votes,
            'type' => $this->type,
            'dvd' => $this->dvd,
            'box_office' => $this->box_office,
            'production' => $this->production,
            'website' => $this->website,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
