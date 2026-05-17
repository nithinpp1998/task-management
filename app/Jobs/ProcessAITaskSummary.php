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

    public $tries = 3; // Retry handling
    public $backoff = 10; // 10 seconds backoff
    
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
        try {
            $result = $aiService->generateSummaryAndPriority($this->task);
            
            $this->task->update([
                'ai_summary' => $result['ai_summary'],
                'ai_priority' => $result['ai_priority'],
            ]);
            
        } catch (Exception $e) {
            Log::error("Failed to process AI summary for task {$this->task->id}: {$e->getMessage()}");
            throw $e;
        }
    }
}
