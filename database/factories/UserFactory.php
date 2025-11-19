<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'password' => Hash::make('password'),
            'role' => $this->faker->randomElement(['customer', 'vendor']),
            'email_verified_at' => now(),
            'status' => 'active',
            'last_login_at' => $this->faker->optional()->dateTimeThisMonth(),
            'remember_token' => Str::random(10),
        ];
    }

    public function customer()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'customer',
            ];
        });
    }

    public function vendor()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'vendor',
            ];
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'admin',
                'email' => 'admin@example.com',
            ];
        });
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}