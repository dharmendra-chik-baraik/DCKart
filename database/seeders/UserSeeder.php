<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '9876543210',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Create Vendor Users
        User::factory(15)->vendor()->create();

        // Create Customer Users
        User::factory(100)->customer()->create();

        echo "Users seeded: " . User::count() . "\n";
    }
}