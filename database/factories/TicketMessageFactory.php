<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'ticket_id' => \App\Models\Ticket::factory(),
            'sender_id' => \App\Models\User::factory(),
            'message' => $this->faker->paragraph(),
            'attachment' => $this->faker->optional()->filePath(),
            'created_at' => $this->faker->dateTimeThisMonth(),
            'updated_at' => $this->faker->dateTimeThisMonth(),
        ];
    }

    public function withAttachment(): static
    {
        return $this->state(fn (array $attributes) => [
            'attachment' => 'attachments/' . $this->faker->word() . '.pdf',
        ]);
    }
}