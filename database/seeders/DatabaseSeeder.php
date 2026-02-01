<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test UserResource',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Admin UserResource',
            'email' => 'admin@example.com',
            'role' => 1,
        ]);

        // Seed movies
        Movie::factory(50)->create();
    }
}
