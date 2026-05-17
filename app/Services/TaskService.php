<?php

namespace App\Services;

use App\Jobs\ProcessAITaskSummary;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class TaskService
{
    public function __construct(protected TaskRepositoryInterface $taskRepository)
    {
    }

    public function getAllTasks(array $filters = [])
    {
        return $this->taskRepository->all($filters);
    }

    public function getTask(int $id)
    {
        return $this->taskRepository->find($id);
    }

    public function createTask(array $data)
    {
        DB::beginTransaction();
        try {
            $task = $this->taskRepository->create($data);
            
            // Dispatch AI summary job
            ProcessAITaskSummary::dispatch($task);

            DB::commit();
            return $task;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateTask(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $task = $this->taskRepository->update($id, $data);
            DB::commit();
            return $task;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateTaskStatus(int $id, string $status)
    {
        return $this->taskRepository->updateStatus($id, $status);
    }
    
    public function deleteTask(int $id)
    {
        return $this->taskRepository->delete($id);
    }
}
