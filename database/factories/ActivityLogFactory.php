<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ActivityLogFactory extends Factory
{
    public function definition(): array
    {
        $modules = ['product', 'order', 'user', 'vendor', 'category', 'payment'];
        $actions = ['created', 'updated', 'deleted', 'viewed', 'approved', 'rejected'];
        
        return [
            'id' => Str::uuid(),
            'user_id' => \App\Models\User::factory(),
            'action' => $this->faker->randomElement($actions),
            'module' => $this->faker->randomElement($modules),
            'description' => $this->faker->sentence(),
            'ip_address' => $this->faker->ipv4(),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear(),
        ];
    }

    public function forModule(string $module): static
    {
        return $this->state(fn (array $attributes) => [
            'module' => $module,
        ]);
    }

    public function forAction(string $action): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => $action,
        ]);
    }
}