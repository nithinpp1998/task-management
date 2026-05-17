<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Models\Task;

class DashboardController extends Controller
{
    public function __construct(protected TaskService $taskService)
    {
    }

    public function index(Request $request)
    {
        $query = Task::query();

        if (!$request->user()->isAdmin()) {
            $query->where('assigned_to', $request->user()->id);
        }

        $totalTasks = (clone $query)->count();
        $completedTasks = (clone $query)->where('status', 'completed')->count();
        $pendingTasks = (clone $query)->where('status', 'pending')->count();
        $inProgressTasks = (clone $query)->where('status', 'in_progress')->count();
        $highPriorityTasks = (clone $query)->where('priority', 'high')->count();

        // Prepare data for the monthly completion chart (mock data for now, or group by month)
        $monthlyData = [
            'Jan' => rand(5, 20),
            'Feb' => rand(5, 20),
            'Mar' => rand(5, 20),
            'Apr' => rand(5, 20),
            'May' => rand(5, 20),
        ];

        return view('dashboard', compact(
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'inProgressTasks',
            'highPriorityTasks',
            'monthlyData'
        ));
    }
}
