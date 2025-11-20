<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // I create an admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@800words.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
