<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected TaskService $taskService)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'priority']);

        if (! $request->user()->isAdmin()) {
            $filters['user_id'] = $request->user()->id;
        }

        $tasks = $this->taskService->getAllTasks($filters);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $this->authorize('create', Task::class);
        $users = User::where('role', 'user')->get();

        return view('tasks.create', compact('users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $this->taskService->createTask($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $users = User::where('role', 'user')->get();

        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->taskService->updateTask($task->id, $request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        $this->taskService->updateTaskStatus($task->id, $request->validated('status'));

        return redirect()->back()->with('success', 'Task status updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $this->taskService->deleteTask($task->id);

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
