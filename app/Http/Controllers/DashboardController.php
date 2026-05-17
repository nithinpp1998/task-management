<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected TaskService $taskService)
    {
    }

    public function index(Request $request)
    {
        $userId = $request->user()->isAdmin() ? null : $request->user()->id;
        $stats = $this->taskService->getTaskStats($userId);

        return view('dashboard', $stats);
    }
}
