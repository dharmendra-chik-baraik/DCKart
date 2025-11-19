<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorPayoutFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'vendor_id' => \App\Models\VendorProfile::factory(),
            'amount' => $this->faker->numberBetween(1000, 50000),
            'status' => $this->faker->randomElement(['pending', 'processed', 'failed']),
            'transaction_id' => $this->faker->optional()->regexify('TXN[0-9]{10}'),
            'remarks' => $this->faker->optional()->sentence(),
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    public function processed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'processed',
                'transaction_id' => 'TXN' . $this->faker->unique()->numberBetween(1000000000, 9999999999),
            ];
        });
    }

    public function failed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'failed',
            ];
        });
    }
}