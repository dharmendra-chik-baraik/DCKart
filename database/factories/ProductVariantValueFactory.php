<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantValueFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'variant_id' => \App\Models\ProductVariant::factory(),
            'value' => $this->faker->word(),
            'price_adjustment' => $this->faker->numberBetween(-500, 500),
            'stock' => $this->faker->numberBetween(0, 50),
        ];
    }

    public function forColor()
    {
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White', 'Silver', 'Gold'];
        
        return $this->state(function (array $attributes) use ($colors) {
            return [
                'value' => $this->faker->randomElement($colors),
                'price_adjustment' => $this->faker->numberBetween(-100, 100),
            ];
        });
    }

    public function forSize()
    {
        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        
        return $this->state(function (array $attributes) use ($sizes) {
            return [
                'value' => $this->faker->randomElement($sizes),
                'price_adjustment' => $this->faker->numberBetween(-50, 50),
            ];
        });
    }

    public function forStorage()
    {
        $storages = ['64GB', '128GB', '256GB', '512GB', '1TB'];
        
        return $this->state(function (array $attributes) use ($storages) {
            return [
                'value' => $this->faker->randomElement($storages),
                'price_adjustment' => $this->faker->numberBetween(500, 2000),
            ];
        });
    }

    public function outOfStock()
    {
        return $this->state(function (array $attributes) {
            return [
                'stock' => 0,
            ];
        });
    }
}