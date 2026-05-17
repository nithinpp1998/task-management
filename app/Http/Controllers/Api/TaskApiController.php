<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskApiController extends Controller
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

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());

        return new TaskResource($task);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        $updatedTask = $this->taskService->updateTaskStatus($task->id, $request->status);

        return new TaskResource($updatedTask);
    }

    public function aiSummary(Task $task)
    {
        $this->authorize('view', $task);

        return response()->json([
            'data' => [
                'ai_summary'  => $task->ai_summary,
                'ai_priority' => $task->ai_priority,
            ],
        ]);
    }
}
