<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed a test user using the factory
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed an admin user with custom password
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Qwerty@123#'),
            'remember_token' => Str::random(10),
        ]);
    }
}
