<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::select('id', 'title', 'status', 'project_id', 'updated_at')->with(['project' =>  function ($query) {
            $query->select('id', 'name');
        }])->get();

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
