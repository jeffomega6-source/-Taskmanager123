<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::query()->orderByRaw("status = 'Completed'")->orderByRaw('due_date IS NULL')->orderBy('due_date')->latest()->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'taskCount' => $tasks->count(),
            'pendingCount' => $tasks->where('status', 'Pending')->count(),
            'completedCount' => $tasks->where('status', 'Completed')->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Task::create($this->validatedData($request));
        return to_route('tasks.index')->with('success', 'Task added to your list.');
    }

    public function edit(Task $task): View
    {
        $tasks = Task::query()->latest()->get();
        return view('tasks.index', [
            'tasks' => $tasks,
            'taskCount' => $tasks->count(),
            'pendingCount' => $tasks->where('status', 'Pending')->count(),
            'completedCount' => $tasks->where('status', 'Completed')->count(),
            'editingTask' => $task,
        ]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($this->validatedData($request));
        return to_route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();
        return to_route('tasks.index')->with('success', 'Task removed from your list.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $task->update($request->validate(['status' => ['required', 'in:Pending,Completed']]));
        return to_route('tasks.index')->with('success', 'Task status updated.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'task_name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:Pending,Completed'],
            'due_date' => ['nullable', 'date'],
        ]);
    }
}