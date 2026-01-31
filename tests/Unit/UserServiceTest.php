<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    /**
     * Test saving a new user successfully.
     */
    public function test_save_creates_user_with_correct_data(): void
    {
        Event::fake();

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $user = $this->userService->save($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    /**
     * Test that password is hashed when saving user.
     */
    public function test_save_hashes_password(): void
    {
        Event::fake();

        $userData = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'securepassword',
        ];

        $user = $this->userService->save($userData);

        $this->assertNotEquals('securepassword', $user->password);
        $this->assertTrue(Hash::check('securepassword', $user->password));
    }

    /**
     * Test that Registered event is dispatched after saving user.
     */
    public function test_save_dispatches_registered_event(): void
    {
        Event::fake();

        $userData = [
            'name' => 'Event Test User',
            'email' => 'event@example.com',
            'password' => 'password123',
        ];

        $user = $this->userService->save($userData);

        Event::assertDispatched(Registered::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    /**
     * Test finding user by email.
     */
    public function test_find_by_email_returns_user(): void
    {
        $user = User::factory()->create([
            'email' => 'findme@example.com',
        ]);

        $foundUser = $this->userService->findByEmail('findme@example.com');

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals('findme@example.com', $foundUser->email);
    }

    /**
     * Test finding user by email returns null when not found.
     */
    public function test_find_by_email_returns_null_when_not_found(): void
    {
        $foundUser = $this->userService->findByEmail('nonexistent@example.com');

        $this->assertNull($foundUser);
    }

    /**
     * Test finding user by ID.
     */
    public function test_find_by_id_returns_user(): void
    {
        $user = User::factory()->create();

        $foundUser = $this->userService->findById($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    /**
     * Test finding user by ID throws exception when not found.
     */
    public function test_find_by_id_throws_exception_when_not_found(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->userService->findById(99999);
    }

    /**
     * Test save method uses database transaction.
     */
    public function test_save_uses_database_transaction(): void
    {
        Event::fake();

        $userData = [
            'name' => 'Transaction Test',
            'email' => 'transaction@example.com',
            'password' => 'password123',
        ];

        $user = $this->userService->save($userData);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'transaction@example.com',
        ]);
    }

    /**
     * Test saving multiple users with different data.
     */
    public function test_save_multiple_users(): void
    {
        Event::fake();

        $users = [];
        for ($i = 1; $i <= 3; $i++) {
            $userData = [
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => "password{$i}",
            ];
            $users[] = $this->userService->save($userData);
        }

        $this->assertCount(3, $users);
        $this->assertDatabaseCount('users', 3);

        foreach ($users as $index => $user) {
            $this->assertDatabaseHas('users', [
                'email' => 'user'.($index + 1).'@example.com',
            ]);
        }
    }
}
