<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_task(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($admin)->post('/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'priority' => 'high',
            'status' => 'pending',
            'assigned_to' => $user->id,
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
        ]);
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
}
