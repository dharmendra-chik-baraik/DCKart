<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'coupon_id' => \App\Models\Coupon::factory(),
            'user_id' => \App\Models\User::factory()->customer(),
            'used_at' => $this->faker->dateTimeThisYear(),
        ];
    }

    public function recentlyUsed(): static
    {
        return $this->state(fn (array $attributes) => [
            'used_at' => $this->faker->dateTimeThisMonth(),
        ]);
    }
}