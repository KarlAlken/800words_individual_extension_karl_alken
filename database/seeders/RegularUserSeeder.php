<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RegularUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // I create a regular user for testing
        User::create([
            'name' => 'Test User',
            'email' => 'user@800words.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
