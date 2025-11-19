<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderItemFactory extends Factory
{
    public function definition()
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->numberBetween(100, 2000);
        $total = $quantity * $price;

        return [
            'id' => Str::uuid(),
            'order_id' => \App\Models\Order::factory(),
            'product_id' => \App\Models\Product::factory(),
            'variant_value_id' => $this->faker->optional()->passthrough(\App\Models\ProductVariantValue::factory()),
            'vendor_id' => \App\Models\VendorProfile::factory(),
            'quantity' => $quantity,
            'price' => $price,
            'total' => $total,
        ];
    }

    public function withVariant()
    {
        return $this->state(function (array $attributes) {
            return [
                'variant_value_id' => \App\Models\ProductVariantValue::factory(),
            ];
        });
    }

    public function withoutVariant()
    {
        return $this->state(function (array $attributes) {
            return [
                'variant_value_id' => null,
            ];
        });
    }
}