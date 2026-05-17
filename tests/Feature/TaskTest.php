<?php

namespace Tests\Feature;

use App\Jobs\ProcessAITaskSummary;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Clear cache before each test to ensure predictable caching behavior
        Cache::flush();
    }

    public function test_admin_can_view_all_tasks(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Task::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/tasks');

        $response->assertStatus(200);
        $response->assertViewHas('tasks');
        $this->assertCount(3, $response->viewData('tasks'));
    }

    public function test_user_can_only_view_own_tasks(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        
        Task::factory()->count(2)->create(['assigned_to' => $user1->id]);
        Task::factory()->count(3)->create(['assigned_to' => $user2->id]);

        $response = $this->actingAs($user1)->get('/tasks');

        $response->assertStatus(200);
        $this->assertCount(2, $response->viewData('tasks'));
    }

    public function test_admin_can_create_task_and_dispatches_ai_job(): void
    {
        Queue::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($admin)->post('/tasks', [
            'title' => 'Test AI Task',
            'description' => 'Test Description',
            'priority' => 'high',
            'status' => 'pending',
            'assigned_to' => $user->id,
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test AI Task',
        ]);

        Queue::assertPushed(ProcessAITaskSummary::class, function ($job) {
            return $job->task->title === 'Test AI Task';
        });
    }

    public function test_user_cannot_create_task(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Test Task',
            'priority' => 'high',
            'assigned_to' => $user->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_task(): void
    {
        Queue::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $task = Task::factory()->create(['title' => 'Old Title', 'description' => 'Old Desc']);

        $response = $this->actingAs($admin)->put("/tasks/{$task->id}", [
            'title' => 'New Title',
            'description' => 'New Desc',
            'priority' => 'low',
            'status' => 'in_progress',
            'assigned_to' => $task->assigned_to,
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'New Title',
            'description' => 'New Desc',
        ]);

        // Job should be pushed since description changed
        Queue::assertPushed(ProcessAITaskSummary::class);
    }

    public function test_updating_task_without_description_change_does_not_dispatch_job(): void
    {
        Queue::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $task = Task::factory()->create(['title' => 'Old Title', 'description' => 'Same Desc']);

        $this->actingAs($admin)->put("/tasks/{$task->id}", [
            'title' => 'New Title',
            'description' => 'Same Desc',
            'priority' => 'low',
            'status' => 'in_progress',
            'assigned_to' => $task->assigned_to,
        ]);

        // Job should NOT be pushed since description didn't change
        Queue::assertNotPushed(ProcessAITaskSummary::class);
    }

    public function test_user_can_update_own_task_status(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $task = Task::factory()->create(['assigned_to' => $user->id, 'status' => 'pending']);

        $response = $this->actingAs($user)->patch("/tasks/{$task->id}/status", [
            'status' => 'completed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    public function test_user_cannot_update_others_task(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        $task = Task::factory()->create(['assigned_to' => $user2->id]);

        $response = $this->actingAs($user1)->put("/tasks/{$task->id}", [
            'title' => 'Hack Title',
            'priority' => 'high',
            'status' => 'completed',
            'assigned_to' => $user2->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_task(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $task = Task::factory()->create();

        $response = $this->actingAs($admin)->delete("/tasks/{$task->id}");

        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_task(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $task = Task::factory()->create(['assigned_to' => $user->id]);

        $response = $this->actingAs($user)->delete("/tasks/{$task->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}
