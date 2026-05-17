<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(array $filters = [])
    {
        $cacheKey = 'tasks_' . md5(json_encode($filters) . '_' . request()->get('page', 1));
        
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 60, function () use ($filters) {
            $query = Task::with('user');

            if (isset($filters['search'])) {
                $query->where('title', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            }

            if (isset($filters['status']) && $filters['status'] !== 'all') {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['priority']) && $filters['priority'] !== 'all') {
                $query->where('priority', $filters['priority']);
            }
            
            if (isset($filters['user_id'])) {
                $query->where('assigned_to', $filters['user_id']);
            }

            return $query->latest()->paginate(10);
        });
    }

    public function find(int $id)
    {
        return Task::findOrFail($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update(int $id, array $data)
    {
        $task = $this->find($id);
        $task->update($data);
        return $task;
    }

    public function delete(int $id)
    {
        $task = $this->find($id);
        return $task->delete();
    }

    public function updateStatus(int $id, string $status)
    {
        $task = $this->find($id);
        $task->update(['status' => $status]);
        return $task;
    }
}
