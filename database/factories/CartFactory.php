<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => \App\Models\User::factory()->customer(),
            'product_id' => \App\Models\Product::factory(),
            'variant_value_id' => $this->faker->optional()->passthrough(\App\Models\ProductVariantValue::factory()),
            'quantity' => $this->faker->numberBetween(1, 3),
        ];
    }

    public function withVariant(): static
    {
        return $this->state(fn (array $attributes) => [
            'variant_value_id' => \App\Models\ProductVariantValue::factory(),
        ]);
    }

    public function withoutVariant(): static
    {
        return $this->state(fn (array $attributes) => [
            'variant_value_id' => null,
        ]);
    }

    public function multipleQuantity(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $this->faker->numberBetween(2, 5),
        ]);
    }
}