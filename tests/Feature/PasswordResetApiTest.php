<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PasswordResetApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function send_reset_link_validates_email(): void
    {
        $response = $this->postJson('/api/forgot-password', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $response = $this->postJson('/api/forgot-password', ['email' => 'not-an-email']);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function reset_password_validates_request(): void
    {
        $response = $this->postJson('/api/reset-password', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['token', 'email', 'password']);
    }

    #[Test]
    public function reset_password_validates_password_confirmation(): void
    {
        $response = $this->postJson('/api/reset-password', [
            'token' => 'some-token',
            'email' => 'test@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    #[Test]
    public function can_reset_password(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->postJson('/api/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Uncompromised-Item-99!',
            'password_confirmation' => 'Uncompromised-Item-99!',
        ]);

        $response->assertOk()
            ->assertJson(['message' => 'Your password has been reset.']);

        $this->assertTrue(Hash::check('Uncompromised-Item-99!', $user->fresh()->password));
    }
}
