<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imdb_id' => 'tt'.$this->faker->numberBetween(1000000, 9999999),
            'title' => $this->faker->sentence(3),
            'year' => $this->faker->year(),
            'rated' => $this->faker->randomElement(['G', 'PG', 'PG-13', 'R', 'NC-17']),
            'released' => $this->faker->date(),
            'runtime' => $this->faker->numberBetween(60, 240).' min',
            'genre' => implode(', ', $this->faker->words(3)),
            'director' => $this->faker->name(),
            'writer' => $this->faker->name(),
            'actors' => implode(', ', [$this->faker->name(), $this->faker->name(), $this->faker->name()]),
            'plot' => $this->faker->paragraph(),
            'language' => $this->faker->languageCode(),
            'country' => $this->faker->country(),
            'awards' => 'Won '.$this->faker->numberBetween(0, 5).' Oscars',
            'poster' => $this->faker->imageUrl(300, 450, 'movies'),
            'metascore' => $this->faker->numberBetween(0, 100),
            'imdb_rating' => $this->faker->randomFloat(1, 1, 10),
            'imdb_votes' => (string) $this->faker->numberBetween(1000, 1000000),
            'type' => 'movie',
            'dvd' => $this->faker->date(),
            'box_office' => '$'.number_format($this->faker->numberBetween(1000000, 1000000000)),
            'production' => $this->faker->company(),
            'website' => $this->faker->url(),
        ];
    }
}
