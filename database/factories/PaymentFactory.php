<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $amount = $this->faker->numberBetween(100, 10000);
        
        return [
            'id' => Str::uuid(),
            'order_id' => \App\Models\Order::factory(),
            'user_id' => \App\Models\User::factory()->customer(),
            'amount' => $amount,
            'payment_method' => $this->faker->randomElement(['credit_card', 'debit_card', 'paypal', 'cod', 'bank_transfer']),
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']), 
            'transaction_id' => $this->faker->optional()->regexify('TXN[0-9]{10}'),
            'payment_details' => $this->faker->optional()->text(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'completed',
            'transaction_id' => 'TXN' . $this->faker->unique()->numberBetween(1000000000, 9999999999),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'failed',
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'refunded',
        ]);
    }

    public function cod(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cod',
            'payment_status' => 'pending',
        ]);
    }
}