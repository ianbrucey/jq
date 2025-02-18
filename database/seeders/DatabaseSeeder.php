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
        // Create a test user if one doesn't already exist
        if (!User::where('email', 'ian@yopmail.com')->exists()) {
            User::create([
                'name' => 'Ian Bruce',
                'email' => 'ian@yopmail.com',
                'password' => Hash::make('beasty09'),
            ]);
        }
    }
}
