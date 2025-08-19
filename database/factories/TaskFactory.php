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
            'project_id' => \App\Models\Project::factory(), // Creates a new Project and uses its id
            'title' => $this->faker->sentence,
            'due_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['Pending','Completed', 'Inactive']),
        ];
    }
}
