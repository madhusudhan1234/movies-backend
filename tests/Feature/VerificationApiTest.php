<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VerificationApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_verify_email_with_valid_hash(): void
    {
        Event::fake();
        $user = User::factory()->unverified()->create();

        $hash = sha1($user->getEmailForVerification());
        $url = "/api/email/verify/{$user->id}/{$hash}";

        $this->actingAs($user);

        $response = $this->getJson($url);

        $response->assertOk()
            ->assertJson(['message' => 'Email verified successfully.']);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        Event::assertDispatched(Verified::class);
    }

    #[Test]
    public function fails_verification_with_invalid_hash(): void
    {
        $user = User::factory()->unverified()->create();
        $url = "/api/email/verify/{$user->id}/invalid-hash";

        $this->actingAs($user);
        $response = $this->getJson($url);

        $response->assertForbidden()
            ->assertJson(['message' => 'Invalid verification link.']);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    #[Test]
    public function returns_message_if_already_verified_verify_endpoint(): void
    {
        $user = User::factory()->create(); // verified by default
        $hash = sha1($user->getEmailForVerification());
        $url = "/api/email/verify/{$user->id}/{$hash}";

        $this->actingAs($user);
        $response = $this->getJson($url);

        $response->assertOk()
            ->assertJson(['message' => 'Email already verified.']);
    }

    #[Test]
    public function can_resend_verification_email(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->postJson('/api/email/resend');

        $response->assertOk()
            ->assertJson(['message' => 'Verification link sent.']);
    }

    #[Test]
    public function returns_message_if_already_verified_resend_endpoint(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/email/resend');

        $response->assertOk()
            ->assertJson(['message' => 'Email already verified.']);
    }
}
