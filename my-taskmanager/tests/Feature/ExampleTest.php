<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/tasks');

        $response->assertStatus(200);
    }

    public function test_a_task_can_be_created_and_completed(): void
    {
        $this->post('/tasks', [
            'task_name' => 'Submit project',
            'description' => 'Upload the final repository.',
            'status' => 'Pending',
            'due_date' => '2026-09-30',
        ])->assertRedirect('/tasks');

        $task = Task::first();
        $this->assertSame('Submit project', $task->task_name);

        $this->patch('/tasks/'.$task->id.'/status', ['status' => 'Completed'])
            ->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'Completed']);
    }
}
