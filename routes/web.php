<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $userProjects = Project::with('creator')->where('creator_id', auth()->id())->get();

        return Inertia::render('Dashboard', [
            'user_projects' => $userProjects
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
