<?php

namespace Database\Seeders;

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
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create personnel user
        User::factory()->create([
            'name' => 'Personnel User',
            'email' => 'personnel@example.com',
            'role' => 'personnel',
        ]);

        // Create citizen user
        User::factory()->create([
            'name' => 'Citizen User',
            'email' => 'citizen@example.com',
            'role' => 'citizen',
        ]);
    }
}
