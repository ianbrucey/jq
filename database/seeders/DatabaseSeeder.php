<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('beasty09');
        // Create a test user if one doesn't already exist
        if (!User::where('email', 'ian@yopmail.com')->exists() && !User::where('email', 'test@yopmail.com')->exists()) {
            User::create([
                'name' => 'Ian Bruce',
                'email' => 'ian@yopmail.com',
                'password' => $password,
            ]);

            User::create([
                'name' => 'Test User',
                'email' => 'test@yopmail.com',
                'password' => $password,
            ]);
        }

        $this->call([
            OpenAiProjectSeeder::class
        ]);
    }
}
