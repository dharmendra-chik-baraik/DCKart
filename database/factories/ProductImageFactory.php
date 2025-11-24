<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductImageFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'product_id' => \App\Models\Product::factory(),
            'image_path' => $this->faker->passthrough('https://placehold.co/600x600/6f42c1/ffffff?text=Product+Image'),
            'position' => $this->faker->numberBetween(0, 5),
        ];
    }
}