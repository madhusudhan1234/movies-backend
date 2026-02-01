<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_movie_by_id(): void
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

    public function test_returns_404_when_movie_not_found(): void
    {
        $response = $this->getJson('/api/movies/99999');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Resource not found.',
            ]);
    }

    public function test_returns_404_on_update_when_movie_not_found(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/movies/99999', [
            'title' => 'New Title',
        ]);

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Resource not found.',
            ]);
    }

    public function test_returns_404_on_delete_when_movie_not_found(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/api/movies/99999');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Resource not found.',
            ]);
    }
}
