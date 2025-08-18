<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $page_size = $request->query('page_size', 10);

        $title = $request->query('title');
        $status = $request->query('status');
        $due_date = $request->query('end_date');
        $creator_id = $request->query('creator_id');
        $project_id = $request->query('project_id');

        $query = Task::select('id', 'title', 'status', 'updated_at', 'project_id')
            ->with('project');

        if($title) $query->where('tasks.title', 'like', '%'.$title.'%');
        if($status) $query->where('tasks.status', $status);
        if($due_date) $query->where('tasks.due_date', '<=', Carbon::parse($due_date));
        if($project_id) $query->where('project_id', $project_id);
        if($creator_id) {
            $query->join('projects', 'projects.id', '=', 'tasks.project_id');
            $query->join('users', 'users.id', '=', 'projects.creator_id');
            $query->where('users.id', $creator_id);
            $query->select([
                'tasks.*',
                'projects.name as project_name',
                'projects.id as project_id',
                'users.name as user_name',
                'users.id as user_id',
            ]);
        }

        $tasks =$query->paginate($page_size)
            ->through(fn ($task) => [
                'id' => $task->id,
                'title' => $task->title,
                'status' => $task->status,
                'updated_at' => $task->updated_at,
                'project' => $task->project,
                'can' => [
                    'view' => true,
                    'update' => auth()->user()->can('update', $task),
                    'delete' => auth()->user()->can('delete', $task),
                ]
        ]);
        return Inertia::render('Task/List.All', [
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'string|required',
            'due_date' => 'datetime|required|after_or_equal:today',
            'status' => 'required|in:Pending,Completed,Inactive',
            'project_id' => 'required|integer|exists:projects,id'
        ]);

        $task = Task::create([
            'title'=> $validated['title'],
            'due_date'=> $validated['due_date'],
            'status'=> $validated['status'],
            'project_id'=> $validated['project_id']
        ]);

        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);

        return Inertia::render('Task/Task.Detail', ['task'=> $task]);
    }

    public function detailAsync($id) {
        $task = Task::with('project')->findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        if ($request->isMethod('get')) {
            return Inertia::render('Task/Task.Edit', ['task' => $task]);
        }

         $validated = $request->validate([
            'title' => 'string|required',
            'due_date' => 'datetime|required|after_or_equal:today',
            'status' => 'required|in:Pending,Completed,Inactive',
            'project_id' => 'required|integer|exists:projects,id'
        ]);

        $task->update($validated);

        return inertia::render('Task/Edit', ['task' => $task, 'updated' => true]);
    }

    public function destroy($id)
    {
        // Delete a specific task
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('task.all')->with('deleted', 'The task was deleted successfully.');
    }
}
