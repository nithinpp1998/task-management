<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->model  = config('services.gemini.model', 'gemini-1.5-flash');
    }

    /**
     * Generate an AI summary and priority for a task using the Gemini API.
     */
    public function generateSummaryAndPriority(Task $task): array
    {
        try {
            $prompt = $this->buildPrompt($task);

            $response = Http::timeout(30)->post(
                "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature'      => 0.3,
                        'maxOutputTokens'  => 300,
                    ],
                ]
            );

            if ($response->failed()) {
                Log::warning('Gemini API returned non-success status.', [
                    'task_id' => $task->id,
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                ]);

                return $this->fallbackResponse($task);
            }

            return $this->parseResponse($response->json(), $task);

        } catch (\Exception $e) {
            Log::error('AIService::generateSummaryAndPriority failed.', [
                'task_id' => $task->id,
                'error'   => $e->getMessage(),
            ]);

            return $this->fallbackResponse($task);
        }
    }

    /**
     * Build the Gemini prompt for the task.
     */
    private function buildPrompt(Task $task): string
    {
        $description = $task->description ?: 'No description provided.';

        return <<<PROMPT
You are a task management AI assistant. Analyze the following task and respond with a JSON object only — no markdown, no extra text.

Task Title: {$task->title}
Task Description: {$description}
Current Priority: {$task->priority}
Current Status: {$task->status}

Respond with this exact JSON format:
{
  "ai_summary": "A concise 2-3 sentence summary of what the task involves and what needs to be done.",
  "ai_priority": "low|medium|high"
}

Base the ai_priority on urgency, complexity, and impact. Be specific and helpful in the summary.
PROMPT;
    }

    /**
     * Parse the Gemini API response and extract summary/priority.
     */
    private function parseResponse(array $data, Task $task): array
    {
        try {
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (! $text) {
                Log::warning('Gemini API returned empty text.', ['task_id' => $task->id, 'data' => $data]);
                return $this->fallbackResponse($task);
            }

            // Strip any accidental markdown code fences
            $text = preg_replace('/```json|```/i', '', $text);
            $parsed = json_decode(trim($text), true);

            if (! $parsed || empty($parsed['ai_summary']) || empty($parsed['ai_priority'])) {
                Log::warning('Gemini API returned unparseable JSON.', ['task_id' => $task->id, 'text' => $text]);
                return $this->fallbackResponse($task);
            }

            // Ensure priority is a valid enum value
            $validPriorities = ['low', 'medium', 'high'];
            $priority = strtolower(trim($parsed['ai_priority']));
            if (! in_array($priority, $validPriorities)) {
                $priority = 'medium';
            }

            return [
                'ai_summary'  => trim($parsed['ai_summary']),
                'ai_priority' => $priority,
            ];

        } catch (\Exception $e) {
            Log::error('AIService::parseResponse failed.', ['task_id' => $task->id, 'error' => $e->getMessage()]);
            return $this->fallbackResponse($task);
        }
    }

    /**
     * Fallback response when the API call fails.
     */
    private function fallbackResponse(Task $task): array
    {
        return [
            'ai_summary'  => "Summary generation is currently unavailable for \"{$task->title}\". Please try again later.",
            'ai_priority' => $task->priority ?? 'medium',
        ];
    }
}
