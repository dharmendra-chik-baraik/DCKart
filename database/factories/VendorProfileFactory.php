<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorProfileFactory extends Factory
{
    public function definition()
    {
        $shopName = $this->faker->company();
        
        return [
            'id' => Str::uuid(),
            'user_id' => \App\Models\User::factory(),
            'shop_name' => $shopName,
            'shop_slug' => Str::slug($shopName),
            'description' => $this->faker->paragraph(),
            'logo' => $this->faker->optional()->passthrough('https://placehold.co/200x200/007bff/ffffff?text=' . urlencode(substr($shopName, 0, 10))),
            'cover_image' => $this->faker->optional()->passthrough('https://placehold.co/800x400/28a745/ffffff?text=' . urlencode($shopName)),
            'verified_at' => $this->faker->optional(0.7)->dateTimeThisYear(),
            'gst_number' => $this->faker->optional()->regexify('[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}'),
            'pan_number' => $this->faker->optional()->regexify('[A-Z]{5}[0-9]{4}[A-Z]{1}'),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => 'India',
            'pincode' => $this->faker->postcode(),
            'bank_account_number' => $this->faker->optional()->bankAccountNumber(),
            'bank_name' => $this->faker->optional()->company(),
            'branch' => $this->faker->optional()->city(),
            'ifsc_code' => $this->faker->optional()->regexify('[A-Z]{4}0[A-Z0-9]{6}'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
                'verified_at' => now(),
            ];
        });
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'verified_at' => null,
            ];
        });
    }
}