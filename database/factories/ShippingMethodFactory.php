<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShippingMethodFactory extends Factory
{
    public function definition(): array
    {
        $methods = [
            ['name' => 'Standard Shipping', 'price' => 50, 'delivery_time' => '5-7 business days'],
            ['name' => 'Express Shipping', 'price' => 150, 'delivery_time' => '2-3 business days'],
            ['name' => 'Overnight Shipping', 'price' => 300, 'delivery_time' => 'Next business day'],
            ['name' => 'Free Shipping', 'price' => 0, 'delivery_time' => '7-10 business days'],
        ];

        $method = $this->faker->randomElement($methods);

        return [
            'id' => Str::uuid(),
            'name' => $method['name'],
            'price' => $method['price'],
            'delivery_time' => $method['delivery_time'],
            'status' => true,
        ];
    }

    public function standard(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Standard Shipping',
            'price' => 50,
            'delivery_time' => '5-7 business days',
        ]);
    }

    public function express(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Express Shipping',
            'price' => 150,
            'delivery_time' => '2-3 business days',
        ]);
    }

    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Free Shipping',
            'price' => 0,
            'delivery_time' => '7-10 business days',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}