<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserAddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => \App\Models\User::factory()->customer(),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address_line_1' => $this->faker->streetAddress(),
            'address_line_2' => $this->faker->optional()->secondaryAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => 'India',
            'pincode' => $this->faker->postcode(),
            'type' => $this->faker->randomElement(['home', 'work', 'other']),
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    public function home(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'home',
        ]);
    }

    public function work(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'work',
        ]);
    }
}