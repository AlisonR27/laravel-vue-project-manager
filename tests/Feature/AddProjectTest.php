<?php

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('creates a project successfully', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $data = [
        'name' => 'Meu Projeto Teste',
        'start_date' => Carbon::parse('15-09-2025'),
        'end_date' => Carbon::parse('20-06-2026'),
        'value' => '1000.00',
        'status' => 'Active',
        'creator_id' => $user->id
    ];

    $response = $this->post(route('project.create'), $data);

    $response->assertRedirect(route('project.all'));

    $this->assertDatabaseHas('projects', [
        'name' => 'Meu Projeto Teste',
        'status' => 'Active',
        'creator_id' => $user->id,
    ]);
});

test("users can't delete other's projects", function() {
    $user = User::factory()->create();

    $this->actingAs($user);

    $data = [
        'name' => 'Não tente deletar meu projeto',
        'start_date' => Carbon::parse('2025-09-15'),
        'end_date' => Carbon::parse('2026-06-20'),
        'value' => '1000.00',
        'status' => 'Active',
    ];

    $project = Project::create(array_merge($data, ['creator_id' => $user->id]));

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'creator_id' => $user->id,
    ]);

    $anotherUser = User::factory()->create();
    $this->actingAs($anotherUser);

    $response = $this->delete(route('project.destroy', $project));

    $response->assertStatus(403);

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
    ]);
});
