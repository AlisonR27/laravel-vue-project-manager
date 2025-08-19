<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it("can't create a project with invalid fields", function () {
    $user = User::factory()->create();

    $invalidData = [
        'name' => '',
        'start_date' => null,
        'end_date' => now()->subDay(),
        'value' => -50,
        'status' => 'unknown',
        'creator_id' => $user->id,
    ];

    $this->expectException(\Illuminate\Database\QueryException::class);

    Project::create($invalidData);

});
