<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MovieApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_list_movies_with_pagination(): void
    {
        Movie::factory()->count(20)->create();

        $response = $this->getJson('/api/movies');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'title', 'year'],
                ],
                'metadata' => ['total', 'per_page', 'current_page'],
            ]);

        $this->assertEquals(20, $response->json('metadata.total'));
    }

    #[Test]
    public function can_search_movies(): void
    {
        Movie::factory()->create(['title' => 'The Matrix']);
        Movie::factory()->create(['title' => 'Inception']);
        Movie::factory()->create(['title' => 'Interstellar']);

        $response = $this->getJson('/api/movies?q=Matrix');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'The Matrix']);
    }

    #[Test]
    public function can_get_movie_by_id(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->getJson("/api/movies/{$movie->id}");

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $movie->id,
                    'title' => $movie->title,
                ],
            ]);
    }

    #[Test]
    public function returns_404_when_movie_not_found(): void
    {
        $response = $this->getJson('/api/movies/99999');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Not found.',
            ]);
    }
}
