<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    public function generateSummaryAndPriority(Task $task)
    {
        try {
            // Mocking AI response for the assignment
            // In a real application, you would call OpenAI/Gemini API here
            
            // Example real API call (mocked):
            /*
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Analyze the task and provide a summary and priority (low, medium, high) in JSON format: {"ai_summary": "...", "ai_priority": "..."}'],
                        ['role' => 'user', 'content' => "Title: {$task->title}\nDescription: {$task->description}"]
                    ]
                ]);
            */
            
            // Simulating API delay
            sleep(2);
            
            $priorities = ['low', 'medium', 'high'];
            $randomPriority = $priorities[array_rand($priorities)];

            return [
                'ai_summary' => "This is an AI generated summary for the task: {$task->title}. It focuses on managing backend operations and improving user experience.",
                'ai_priority' => $randomPriority,
            ];
            
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            
            // Fallback response
            return [
                'ai_summary' => 'Failed to generate AI summary.',
                'ai_priority' => 'low',
            ];
        }
    }
}
