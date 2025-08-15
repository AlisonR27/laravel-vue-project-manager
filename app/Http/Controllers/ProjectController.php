<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Throwable;

class ProjectController extends Controller
{
    //
    public function index(): \Inertia\Response
    {
        $projects = Project::select('id', 'name', 'status', 'updated_at', 'creator_id')->with('creator')->get();
        return Inertia::render('Project/List', [
            'projects' => $projects
        ]);
    }

    public function show($id)
    {
        $project = Project::with('creator')->findOrFail($id);

        $taskCount = Task::where('project_id', $id)->count();

        $activeTasks = Task::where('project_id', $id)->where('status', 'Pending')->count();

        $overdueTasks = Task::where('project_id', $id)->where('due_date', '<=', Carbon::today())->count();


        return Inertia::render('Project/Project.Detail', [
            'project' => $project,
            'tasks_bi' => [
                'count' => $taskCount,
                'active' => $activeTasks,
                'overdue' => $overdueTasks
            ]
        ]);
    }

    public function showByUser($user_id) {
        try {
            $projects = Project::where('user_id', $user_id)->get();
            return response()->json(['data' => $projects]);
        } catch (Throwable $e) {
            return response()->json(['error' => 'No projects found'], 200);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=> 'required|string|max:60',
            'start_date' => 'required|datetime',
            'end_date' => 'required|datetime|after_or_equal:start_date',
            'value' => 'required|decimal|min:0',
            'status' => 'in:Active,Inactive',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'value' => $validated['value'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Project created',
            'data' => $project
        ], 201);
    }

    public function update(Request $request, $id): \Inertia\Response
    {
        $project = Project::findOrFail($id);

        if ($request->isMethod('get')) {
            return Inertia::render('Project/Project.Edit', ['project' => $project]);
        }

        $validated = $request->validate([
            'name'=> 'required|string|max:60',
            'start_date' => 'required|date|',
            'end_date' => 'required|date|after_or_equal:start_date',
            'value' => 'required|decimal:2|min:0',
            'status' => 'in:Active,Inactive',
        ]);

        $project->update($validated);

        return inertia::render('Project/Project.Edit', ['project'=> $project, 'updated' => true]);
    }

    public function destroy($id)
    {
        // Delete a project
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('project.all')->with('deleted', 'The project was deleted successfully.');
    }
}
