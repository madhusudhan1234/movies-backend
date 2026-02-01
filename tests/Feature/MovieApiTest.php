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
                'data' => [
                    '*' => ['id', 'title', 'year', 'director'],
                ],
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ]);

        $this->assertEquals(20, $response->json('pagination.total'));
    }

    #[Test]
    public function can_search_movies(): void
    {
        Movie::factory()->create(['title' => 'The Matrix']);
        Movie::factory()->create(['title' => 'Inception']);
        Movie::factory()->create(['director' => 'Christopher Nolan']);

        $response = $this->getJson('/api/movies?q=Matrix');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'The Matrix']);

        $response = $this->getJson('/api/movies?q=Nolan');

        $response->assertOk()
             // Assuming director search is implemented in Service
            ->assertJsonFragment(['director' => 'Christopher Nolan']);
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
    public function can_create_movie(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Interstellar',
            'year' => 2014,
            'director' => 'Christopher Nolan',
            'imdb_id' => 'tt0816692',
        ];

        $response = $this->actingAs($user)->postJson('/api/movies', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['title' => 'Interstellar']);

        $this->assertDatabaseHas('movies', ['title' => 'Interstellar']);
    }

    #[Test]
    public function validates_movie_creation(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/movies', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    #[Test]
    public function returns_500_if_movie_creation_fails(): void
    {
        $user = User::factory()->create();

        $this->mock(\App\Services\Movie\MovieServiceInterface::class, function ($mock) {
            $mock->shouldReceive('createMovie')->andThrow(new \Exception('Database error'));
        });

        $response = $this->actingAs($user)->postJson('/api/movies', [
            'title' => 'Interstellar',
            'year' => 2014,
            'director' => 'Christopher Nolan',
        ]);

        $response->assertStatus(500)
            ->assertJson(['message' => 'Unable to create movie']);
    }

    #[Test]
    public function can_update_movie(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($user)->putJson("/api/movies/{$movie->id}", [
            'title' => 'New Title',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['title' => 'New Title']);

        $this->assertDatabaseHas('movies', [
            'id' => $movie->id,
            'title' => 'New Title',
        ]);
    }

    #[Test]
    public function can_delete_movie(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/movies/{$movie->id}");

        $response->assertOk()
            ->assertJson(['message' => 'Movie deleted successfully']);

        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }

    #[Test]
    public function guests_cannot_modify_movies(): void
    {
        $movie = Movie::factory()->create();

        $this->postJson('/api/movies', ['title' => 'Test'])->assertUnauthorized();
        $this->putJson("/api/movies/{$movie->id}", ['title' => 'New'])->assertUnauthorized();
        $this->deleteJson("/api/movies/{$movie->id}")->assertUnauthorized();
    }

    #[Test]
    public function returns_404_when_movie_not_found(): void
    {
        $response = $this->getJson('/api/movies/99999');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Resource not found.',
            ]);
    }

    #[Test]
    public function returns_404_on_update_when_movie_not_found(): void
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

    #[Test]
    public function returns_404_on_delete_when_movie_not_found(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/api/movies/99999');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Resource not found.',
            ]);
    }
}
