<?php

namespace Tests\Feature;

use App\Category;
use App\LifeArea;
use App\Subtask;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCompletionTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $lifeArea = LifeArea::create(['title' => 'Work']);
        $this->category = Category::create([
            'life_area_id' => $lifeArea->id,
            'title' => 'Development',
        ]);
    }

    public function test_a_task_can_be_completed_together_with_all_its_subtasks(): void
    {
        $task = $this->createTask();
        $firstSubtask = $this->createSubtask($task, 'Implement');
        $secondSubtask = $this->createSubtask($task, 'Review');

        $this->patchJson('/api/v1/tasks/'.$task->id.'/complete_with_subtasks')
            ->assertOk()
            ->assertJsonPath('success', true);

        $task->refresh();
        $firstSubtask->refresh();
        $secondSubtask->refresh();

        $this->assertTrue((bool) $task->completed);
        $this->assertNotNull($task->completed_at);
        $this->assertTrue((bool) $firstSubtask->completed);
        $this->assertNotNull($firstSubtask->completed_at);
        $this->assertTrue((bool) $secondSubtask->completed);
        $this->assertNotNull($secondSubtask->completed_at);
    }

    public function test_completing_a_task_with_subtasks_keeps_the_existing_completion_date_of_a_subtask(): void
    {
        $task = $this->createTask();
        $completedAt = now()->subDays(3);
        $completedSubtask = $this->createSubtask($task, 'Already done');
        $completedSubtask->update([
            'completed' => true,
            'completed_at' => $completedAt,
        ]);

        $this->patchJson('/api/v1/tasks/'.$task->id.'/complete_with_subtasks')
            ->assertOk();

        $completedSubtask->refresh();

        $this->assertTrue((bool) $completedSubtask->completed);
        $this->assertSame($completedAt->toDateTimeString(), (string) $completedSubtask->completed_at);
    }

    public function test_deleted_subtasks_are_not_completed(): void
    {
        $task = $this->createTask();
        $deletedSubtask = $this->createSubtask($task, 'Removed');
        $deletedSubtask->update(['active' => 0]);

        $this->patchJson('/api/v1/tasks/'.$task->id.'/complete_with_subtasks')
            ->assertOk();

        $deletedSubtask->refresh();

        $this->assertFalse((bool) $deletedSubtask->completed);
        $this->assertNull($deletedSubtask->completed_at);
    }

    public function test_completing_an_unknown_task_with_subtasks_returns_not_found(): void
    {
        $this->patchJson('/api/v1/tasks/999/complete_with_subtasks')
            ->assertNotFound()
            ->assertJsonPath('message', 'Task not found');
    }

    private function createTask(): Task
    {
        return Task::create([
            'life_area_id' => $this->category->life_area_id,
            'category_id' => $this->category->id,
            'title' => 'Ticket',
            'points_upon_completion' => 40,
            'active' => 1,
        ]);
    }

    private function createSubtask(Task $task, string $title): Subtask
    {
        return Subtask::create([
            'life_area_id' => $task->life_area_id,
            'category_id' => $task->category_id,
            'task_id' => $task->id,
            'title' => $title,
            'points_upon_completion' => 10,
            'active' => 1,
        ]);
    }
}
