<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()->count(5)->create();

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ]);
        $users->push($user);

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
