<?php

namespace Database\Factories;

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
            'Title' => fake()->paragraph(1),
            'Description' => fake()->text(20),
            'Status' => fake()->boolean(),
            'Due_Date' =>fake()->date(),
            'project_id' => rand(1, 10),
            'Assignee' => rand(1, 10),
            'Assigner' => rand(1, 10),
        ];
    }
}
