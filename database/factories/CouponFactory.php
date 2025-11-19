<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        $discountType = $this->faker->randomElement(['percentage', 'fixed']);
        $discountValue = $discountType === 'percentage' 
            ? $this->faker->numberBetween(5, 50)
            : $this->faker->numberBetween(100, 1000);

        return [
            'id' => Str::uuid(),
            'code' => Str::upper($this->faker->bothify('COUPON##??')),
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'min_order_value' => $this->faker->numberBetween(500, 2000),
            'max_discount' => $discountType === 'percentage' ? $this->faker->numberBetween(200, 1000) : null,
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
            'usage_limit' => $this->faker->optional()->numberBetween(10, 100),
            'used_count' => 0,
            'status' => true,
        ];
    }

    public function percentageDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'percentage',
            'discount_value' => $this->faker->numberBetween(5, 50),
            'max_discount' => $this->faker->numberBetween(200, 1000),
        ]);
    }

    public function fixedDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'fixed',
            'discount_value' => $this->faker->numberBetween(100, 1000),
            'max_discount' => null,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => $this->faker->dateTimeBetween('-6 months', '-2 months'),
            'end_date' => $this->faker->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+2 months', '+6 months'),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}