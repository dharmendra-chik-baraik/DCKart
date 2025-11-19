<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition()
    {
        $name = $this->faker->unique()->words(2, true);
        
        return [
            'id' => Str::uuid(),
            'parent_id' => null,
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => $this->faker->randomElement(['fas-box', 'fas-tshirt', 'fas-mobile', 'fas-laptop', 'fas-home', 'fas-heart']),
            'image' => $this->faker->optional()->imageUrl(400, 300, 'categories'),
            'description' => $this->faker->sentence(),
            'status' => true,
        ];
    }

    public function withParent()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => \App\Models\Category::factory(),
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => false,
            ];
        });
    }
}