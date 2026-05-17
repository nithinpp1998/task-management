<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Services\TaskService;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected TaskService $taskService,
        protected UserService $userService
    ) {}

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
        $this->authorize('create', \App\Models\Task::class); // Authorize uses class string
        $users = $this->userService->getUsersByRole('user');

        return view('tasks.create', compact('users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $this->taskService->createTask($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show($id)
    {
        $task = $this->taskService->getTask($id);
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    public function edit($id)
    {
        $task = $this->taskService->getTask($id);
        $this->authorize('update', $task);
        $users = $this->userService->getUsersByRole('user');

        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = $this->taskService->getTask($id);
        $this->authorize('update', $task);

        $this->taskService->updateTask($id, $request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, $id)
    {
        $task = $this->taskService->getTask($id);
        $this->authorize('updateStatus', $task);

        $this->taskService->updateTaskStatus($id, $request->validated('status'));

        return redirect()->back()->with('success', 'Task status updated successfully.');
    }

    public function destroy($id)
    {
        $task = $this->taskService->getTask($id);
        $this->authorize('delete', $task);
        $this->taskService->deleteTask($id);

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
