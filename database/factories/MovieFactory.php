<?php

namespace Database\Factories;

use App\Enums\MovieCreditsRole;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\People;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imdb_id'               => 'tt'.$this->faker->numberBetween(1000000, 9999999),
            'title'                 => $this->faker->sentence(3),
            'year'                  => (string) $this->faker->year(),
            'rated'                 => $this->faker->randomElement(['G', 'PG', 'PG-13', 'R', 'NC-17']),
            'released'              => $this->faker->date(),
            'runtime'               => $this->faker->numberBetween(60, 240).' min',
            'plot'                  => $this->faker->paragraph(),
            'language'              => $this->faker->languageCode(),
            'country'               => $this->faker->country(),
            'awards'                => $this->faker->words(3),
            'metascore'             => (string) $this->faker->numberBetween(0, 100),
            'imdb_rating'           => (string) $this->faker->randomFloat(1, 1, 10),
            'imdb_votes'            => (string) $this->faker->numberBetween(1000, 1000000),
            'ratings'               => [
                ['Source' => 'Internet Movie Database', 'Value' => '8.0/10'],
                ['Source' => 'Rotten Tomatoes', 'Value' => '80%'],
            ],
            'type'                  => 'movie',
            'dvd'                   => $this->faker->date(),
            'box_office_collection' => $this->faker->numberBetween(1000000, 1000000000),
            'production'            => $this->faker->company(),
            'website'               => $this->faker->url(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Movie $movie) {
            $movie->genres()->attach(Genre::factory()->count(rand(1, 3))->create());

            $people = People::factory()->count(rand(3, 10))->create();
            foreach ($people as $person) {
                $movie->credits()->attach($person, ['role' => $this->faker->randomElement(MovieCreditsRole::cases())]);
            }

            try {
                $movie->addMediaFromUrl('https://picsum.photos/600/400')->toMediaCollection(Movie::POSTER);
            } catch (\Throwable $exception) {
                dd($exception);
            }
        });
    }
}
