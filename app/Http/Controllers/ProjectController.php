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
    /**
     * @param Request $request
     * @return \Inertia\Response
     * Handle the display of all the projects.
     * When the request is not empty, filters the data to be shown.
     */
    public function index(Request $request): \Inertia\Response
    {
        $alert = session('alert');

        $page_size = $request->query('page_size', 10);

        $search = $request->query('search');
        $status = $request->query('status');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $min_value = $request->query('min_value');
        $max_value = $request->query('max_value');
        $creator_id = $request->query('creator_id');

        $query = Project::select('id', 'name', 'status', 'updated_at', 'creator_id')
            ->with('creator');

        if($search) $query->where('name', 'like', '%'.$search.'%');
        if($status) $query->where('status', $status);
        if($start_date) $query->where('created_at', '>=', Carbon::parse($start_date));
        if($end_date) $query->where('created_at', '<=', Carbon::parse($end_date));
        if($min_value) $query->where('value', '>=', $min_value);
        if($max_value) $query->where('value', '<=', $max_value);
        if($creator_id) $query->where('creator_id', $creator_id);

        $projects =$query->paginate($page_size)
            ->through(fn ($project) => [
                'id' => $project->id,
                'name' => $project->name,
                'status' => $project->status,
                'updated_at' => $project->updated_at,
                'creator' => $project->creator,
                'can' => [
                    'view' => true,
                    'update' => auth()->user()->can('update', $project),
                    'delete' => auth()->user()->can('delete', $project),
                ]
            ]);


        return Inertia::render('Project/List', [
            'projects' => $projects,
            'alert' => $alert
        ]);
    }

    /**
     * @param $id
     * @return \Inertia\Response
     * Shows data of project and it's child tasks
     */
    public function show($id)
    {

        $project = Project::with('creator')->findOrFail($id);

        $tasks = Task::where('project_id', $id);

        $taskCount = $tasks->count();

        $activeTasks = Task::where('project_id', $id)->where('status', 'Pending')->count();

        $overdueTasks = Task::where('project_id', $id)->where('due_date', '<=', Carbon::today())->count();

        return Inertia::render('Project/Project.Detail', [
            'project' => $project,
            'tasks' => $tasks->latest()->take(5)->get(),
            'tasks_bi' => [
                'count' => $taskCount,
                'active' => $activeTasks,
                'overdue' => $overdueTasks
            ],
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
        if ($request->method() === 'GET') {
            return Inertia::render('Project/Project.Create');
        }

        $validated = $request->validate([
            'name'=> 'required|string|max:60',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'value' => 'required|decimal:0,2|min:0',
            'status' => 'in:Active,Inactive',
            'creator_id' => 'required|integer|exists:users,id',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'value' => $validated['value'],
            'status' => $validated['status'],
            'creator_id' => $validated['creator_id'],
        ]);

        return redirect()->route('project.all')->with('alert', [
            'type' => 'success',
            'message' => 'The project was created successfully.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        if (!auth()->user()->can('update', $project)) {
            return redirect()->route('project.all')->with('alert', [
                'type'=>'error',
                'message'=>'You do not have permission to perform this action.'
            ]);
        }

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
        $project = Project::findOrFail($id);
        if (!auth()->user()->can('delete', $project)) {
            return redirect()->route('project.all')->with('alert', [
                    'type' => 'error',
                    'message' => 'You do not have permission to perform this action'
            ]);
        }
        $project->delete();
        return redirect()->route('project.all')->with('alert', [
            'type' => 'success',
            'message' => 'The project was deleted successfully.'
        ]);
    }
}
