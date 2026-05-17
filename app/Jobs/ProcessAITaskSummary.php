<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessAITaskSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Number of seconds to wait before retrying.
     */
    public int $backoff = 15;

    /**
     * Number of seconds the job can run before timing out.
     */
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(public Task $task)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(AIService $aiService): void
    {
        // Mark summary as generating so the UI can show a loading state
        $this->task->update(['ai_summary' => null, 'ai_priority' => null]);

        try {
            // Re-fetch fresh task data in case it was updated between dispatch and execution
            $freshTask = Task::find($this->task->id);

            if (! $freshTask) {
                Log::warning("ProcessAITaskSummary: Task {$this->task->id} no longer exists. Skipping.");
                return;
            }

            $result = $aiService->generateSummaryAndPriority($freshTask);

            $freshTask->update([
                'ai_summary'  => $result['ai_summary'],
                'ai_priority' => $result['ai_priority'],
            ]);

            Log::info("ProcessAITaskSummary: Successfully generated AI summary for task {$freshTask->id}.");

        } catch (Exception $e) {
            Log::error("ProcessAITaskSummary: Failed for task {$this->task->id}.", [
                'error'   => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            throw $e; // Let Laravel's retry mechanism handle it
        }
    }

    /**
     * Handle a job that has failed.
     */
    public function failed(Exception $exception): void
    {
        Log::error("ProcessAITaskSummary: Job permanently failed for task {$this->task->id}.", [
            'error' => $exception->getMessage(),
        ]);

        // Write a graceful fallback summary so the UI never shows null
        Task::where('id', $this->task->id)->update([
            'ai_summary'  => 'AI summary generation failed after multiple attempts. Please try editing and saving the task to retry.',
            'ai_priority' => $this->task->priority ?? 'medium',
        ]);
    }
}
