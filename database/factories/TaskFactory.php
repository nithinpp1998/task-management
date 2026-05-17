<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4, true),
            'description' => $this->faker->paragraph(3),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'due_date' => $this->faker->dateTimeBetween('+1 days', '+60 days')->format('Y-m-d'),
            'assigned_to' => User::where('role', 'user')->value('id') ?? 1,
            'ai_summary' => $this->faker->sentence(12),
            'ai_priority' => $this->faker->randomElement(['low', 'medium', 'high']),
        ];
    }
}
