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

        // Construct the verification URL manually to match controller logic
        // Route pattern likely: /api/email/verify/{id}/{hash}
        // Controller checks: hash_equals($hash, sha1($user->getEmailForVerification()))
        
        $hash = sha1($user->getEmailForVerification());
        $url = "/api/email/verify/{$user->id}/{$hash}";

        // We need to actAs the user usually, or is it a public route? 
        // Typically verify route is protected by `signed` middleware OR just public with hash check.
        // Looking at the controller, it fetches user by ID from route, so it might be guest accessible if signature matches?
        // But standard Laravel verify route often requires auth.
        // Let's assume it requires Auth for now, or at least try without first if routes are unknown.
        // However, standard is: User clicks link, *then* logs in (or is logged in) or the link works for guests if signed.
        // But the controller gets user from service, meaning it doesn't rely on `auth()->user()`.
        
        // Let's try acting as the user to be safe and typical.
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
        // Notification::fake(); // We might verify the notification is sent
        // But simpler to just check response for now
        
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
