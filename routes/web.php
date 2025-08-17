<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $creator_id = auth()->id();

        $userProjects = Project::with('creator')->where('creator_id', $creator_id)->limit(5)->get();

        $userTasks = Task::whereHas('project', function ($query) {
            $query->where('creator_id', auth()->id());
        })->limit(5)->get();

        $totalProjects = $userProjects->count();

        $inProgress = Project::where('creator_id', $creator_id)
            ->where('status', 'Active')
            ->count();

        $delayed = Project::where('creator_id', $creator_id)
            ->where('status', 'Active')
            ->where('end_date', '<', now())
            ->count();

        $totalValueInProgress = Project::where('creator_id', $creator_id)
            ->where('status', 'Active')
            ->sum('value');

        return Inertia::render('Dashboard', [
            'user-projects' => $userProjects,
            'user-tasks' => $userTasks,
            'metrics' => [
                'totalProjects' => $totalProjects,
                'inProgress' => $inProgress,
                'delayed' => $delayed,
                'totalValueInProgress' => $totalValueInProgress
            ]
        ]);
    })->name('dashboard');

    Route::match(['get', 'put'], '/projects/{project}/edit', [ProjectController::class, 'update'])->name('project.update');

    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('project');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');
    Route::get('/projects/', [ProjectController::class, 'index'])->name('project.all');

    Route::get('/tasks/', [TaskController::class, 'index'] )->name('task.all');
    Route::get('/tasks/{task}', [TaskController::class, 'show'] )->name('task.details');
    Route::get('/tasks/{task}/async' , [TaskController::class, 'detailAsync'] )->name('task.async');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('task.destroy');

    Route::match(['get', 'put'], '/task/{project}/edit', [TaskController::class, 'update'])->name('task.update');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
