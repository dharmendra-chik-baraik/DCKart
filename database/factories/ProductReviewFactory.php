<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => \App\Models\User::factory()->customer(),
            'product_id' => \App\Models\Product::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'review' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function highRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(4, 5),
        ]);
    }

    public function lowRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(1, 2),
        ]);
    }

    public function withReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'review' => $this->faker->paragraph(),
        ]);
    }
}