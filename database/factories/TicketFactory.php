<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => \App\Models\User::factory()->customer(),
            'vendor_id' => $this->faker->optional()->passthrough(\App\Models\VendorProfile::factory()),
            'subject' => $this->faker->sentence(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'resolved', 'closed']),
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }

    public function withVendor(): static
    {
        return $this->state(fn (array $attributes) => [
            'vendor_id' => \App\Models\VendorProfile::factory(),
        ]);
    }
}