<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = User::factory()->count(5)->create();
        foreach ($users as $user) {
            $projects = Project::factory()->count(6)->create([
                'creator_id'=> $user->id
            ]);
            foreach ($projects as $project) {
                Task::factory()->count(10)->create([
                   'project_id' => $project->id
                ]);
            }
        }
    }
}
