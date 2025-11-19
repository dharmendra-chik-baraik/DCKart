<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'product_id' => \App\Models\Product::factory(),
            'name' => $this->faker->randomElement(['Color', 'Size', 'Storage', 'RAM', 'Material']),
        ];
    }

    public function color()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Color',
            ];
        });
    }

    public function size()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Size',
            ];
        });
    }

    public function storage()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Storage',
            ];
        });
    }
}