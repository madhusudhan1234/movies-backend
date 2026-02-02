<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavoritesApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_add_movie_to_favorites(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/movies/{$movie->id}/favorite");

        $response->assertOk()
            ->assertJson([
                'message' => 'Movie added to favorites.',
            ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
        ]);

        $pivot = DB::table('favorites')
            ->where('user_id', $user->id)
            ->where('movie_id', $movie->id)
            ->first();

        $this->assertNotNull($pivot->created_at);
        $this->assertNotNull($pivot->updated_at);
    }

    #[Test]
    public function user_cannot_add_same_movie_twice(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $user->favoriteMovies()->attach($movie->id);

        $response = $this->actingAs($user)->postJson("/api/movies/{$movie->id}/favorite");

        $response->assertStatus(409)
            ->assertJson([
                'message' => 'Movie is already in favorites.',
            ]);
    }

    #[Test]
    public function user_can_remove_movie_from_favorites(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $user->favoriteMovies()->attach($movie->id);

        $response = $this->actingAs($user)->deleteJson("/api/movies/{$movie->id}/favorite");

        $response->assertOk()
            ->assertJson([
                'message' => 'Movie removed from favorites.',
            ]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
        ]);
    }

    #[Test]
    public function guest_cannot_access_favorites_routes(): void
    {
        $movie = Movie::factory()->create();

        $this->postJson("/api/movies/{$movie->id}/favorite")->assertUnauthorized();
        $this->deleteJson("/api/movies/{$movie->id}/favorite")->assertUnauthorized();
    }
}
