<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Movie;
use App\Models\User;
use App\Services\Movie\MovieService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MovieServiceTest extends TestCase
{
    use RefreshDatabase;

    private MovieService $movieService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->movieService = app(MovieService::class);
    }

    #[Test]
    public function creates_movie_successfully(): void
    {
        $data = [
            'title' => 'The Dark Knight',
            'year' => 2008,
            'director' => 'Christopher Nolan',
        ];

        $movie = $this->movieService->createMovie($data);

        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertEquals('The Dark Knight', $movie->title);
        $this->assertDatabaseHas('movies', ['title' => 'The Dark Knight']);
    }

    #[Test]
    public function gets_paginated_movies(): void
    {
        Movie::factory()->count(20)->create();

        $movies = $this->movieService->getPaginatedMovies(15);

        $this->assertInstanceOf(LengthAwarePaginator::class, $movies);
        $this->assertEquals(20, $movies->total());
        $this->assertEquals(15, $movies->perPage());
        $this->assertCount(15, $movies->items());
    }

    #[Test]
    public function searches_movies_by_query(): void
    {
        Movie::factory()->create(['title' => 'Alpha']);
        Movie::factory()->create(['title' => 'Alpine']);
        Movie::factory()->create(['title' => 'Bravo']);

        $results = $this->movieService->getPaginatedMovies(10, 'Alp');

        $this->assertCount(2, $results->items()); // Alpha, Alpine
        $this->assertFalse($results->items()[0]->title === 'Bravo');
    }

    #[Test]
    public function gets_movie_by_id(): void
    {
        $movie = Movie::factory()->create();

        $foundMovie = $this->movieService->getMovieById($movie->id);

        $this->assertEquals($movie->id, $foundMovie->id);
    }

    #[Test]
    public function get_movie_by_id_throws_exception_if_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->movieService->getMovieById(999999);
    }

    #[Test]
    public function updates_movie_successfully(): void
    {
        $movie = Movie::factory()->create(['title' => 'Old Title']);

        $updatedMovie = $this->movieService->updateMovie($movie->id, ['title' => 'New Title']);

        $this->assertEquals('New Title', $updatedMovie->title);
        $this->assertDatabaseHas('movies', ['id' => $movie->id, 'title' => 'New Title']);
    }

    #[Test]
    public function deleted_movie_successfully(): void
    {
        $movie = Movie::factory()->create();

        $result = $this->movieService->deleteMovie($movie->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }

    #[Test]
    public function adds_movie_to_favorites(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $this->movieService->addToFavorites($user->id, $movie->id);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
        ]);
    }

    #[Test]
    public function removes_movie_from_favorites(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();
        $user->favoriteMovies()->attach($movie->id);

        $this->movieService->removeFromFavorites($user->id, $movie->id);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
        ]);
    }
}
