<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderStatusLogFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        return [
            'id' => Str::uuid(),
            'order_id' => \App\Models\Order::factory(),
            'status' => $this->faker->randomElement($statuses),
            'changed_by' => $this->faker->randomElement(['system', 'admin', 'vendor', 'customer']),
            'note' => $this->faker->optional()->sentence(),
            'created_at' => $this->faker->dateTimeThisMonth(),
        ];
    }

    public function forStatus(string $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}