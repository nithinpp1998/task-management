<?php

use App\Http\Controllers\Api\TaskApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskApiController::class, 'index']);
    Route::post('/tasks', [TaskApiController::class, 'store']);
    Route::patch('/tasks/{task}/status', [TaskApiController::class, 'updateStatus']);
    Route::get('/tasks/{task}/ai-summary', [TaskApiController::class, 'aiSummary']);
});
