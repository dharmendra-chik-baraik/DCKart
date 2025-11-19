<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $price = $this->faker->numberBetween(100, 5000);
        
        return [
            'id' => Str::uuid(),
            'vendor_id' => \App\Models\VendorProfile::factory(),
            'category_id' => \App\Models\Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => 'SKU-' . $this->faker->unique()->numberBetween(1000, 99999),
            'description' => $this->faker->paragraphs(3, true),
            'short_description' => $this->faker->sentence(),
            'price' => $price,
            'sale_price' => $this->faker->optional(0.3)->numberBetween(50, $price - 10),
            'stock' => $this->faker->numberBetween(0, 200),
            'stock_status' => $this->faker->randomElement(['in_stock', 'out_of_stock', 'backorder']),
            'weight' => $this->faker->randomFloat(2, 0.1, 20),
            'length' => $this->faker->randomFloat(2, 1, 100),
            'width' => $this->faker->randomFloat(2, 1, 100),
            'height' => $this->faker->randomFloat(2, 1, 100),
            'status' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(20),
            'meta_title' => $this->faker->optional()->sentence(),
            'meta_description' => $this->faker->optional()->paragraph(),
            'meta_keywords' => $this->faker->optional()->words(5, true),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
            'stock_status' => 'out_of_stock',
        ]);
    }

    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(10, 100),
            'stock_status' => 'in_stock',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    public function withDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_price' => $this->faker->numberBetween(50, $attributes['price'] - 10),
        ]);
    }
}