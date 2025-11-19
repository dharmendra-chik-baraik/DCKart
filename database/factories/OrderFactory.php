<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(500, 5000);
        $discount = $this->faker->numberBetween(0, 500);
        $shipping = $this->faker->numberBetween(0, 200);
        $tax = ($subtotal - $discount) * 0.18;
        $grandTotal = $subtotal - $discount + $shipping + $tax;

        return [
            'id' => Str::uuid(),
            'order_number' => 'ORD' . $this->faker->unique()->numberBetween(100000, 999999),
            'user_id' => \App\Models\User::factory()->customer(),
            'vendor_id' => \App\Models\VendorProfile::factory(),
            'total_amount' => $subtotal,
            'discount' => $discount,
            'shipping_charge' => $shipping,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']), 
            'order_status' => $this->faker->randomElement(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled']),
            'shipping_method' => $this->faker->randomElement(['standard', 'express', 'overnight']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'debit_card', 'paypal', 'cod', 'bank_transfer']),
            'transaction_id' => $this->faker->optional()->regexify('TXN[0-9]{10}'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'confirmed',
            'payment_status' => 'completed', 
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'delivered',
            'payment_status' => 'completed', 
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'cancelled',
            'payment_status' => $this->faker->randomElement(['refunded', 'failed']),
        ]);
    }

    public function withCod(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cod',
            'payment_status' => 'pending',
        ]);
    }

    public function withOnlinePayment(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => $this->faker->randomElement(['credit_card', 'debit_card', 'paypal']),
            'payment_status' => 'completed',
            'transaction_id' => 'TXN' . $this->faker->unique()->numberBetween(1000000000, 9999999999),
        ]);
    }
}