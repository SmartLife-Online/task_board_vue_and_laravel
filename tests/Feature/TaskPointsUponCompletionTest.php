<?php

namespace Tests\Feature;

use App\Category;
use App\LifeArea;
use App\Subtask;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPointsUponCompletionTest extends TestCase
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

    public function test_a_new_task_without_points_upon_completion_sums_up_its_subtasks(): void
    {
        $response = $this->postJson('/api/v1/tasks/to_category/'.$this->category->id, [
            'title' => 'Ticket',
            'points_upon_completion' => '',
            'subtasks' => [
                ['title' => 'Implement', 'points_upon_completion' => 15],
                ['title' => 'Review', 'points_upon_completion' => 5],
            ],
        ])->assertOk();

        $task = Task::find($response->json('id'));

        $this->assertSame(20, (int) $task->points_upon_completion);
    }

    public function test_a_new_task_keeps_its_given_points_upon_completion(): void
    {
        $response = $this->postJson('/api/v1/tasks/to_category/'.$this->category->id, [
            'title' => 'Ticket',
            'points_upon_completion' => 40,
            'subtasks' => [
                ['title' => 'Implement', 'points_upon_completion' => 15],
            ],
        ])->assertOk();

        $task = Task::find($response->json('id'));

        $this->assertSame(40, (int) $task->points_upon_completion);
    }

    public function test_an_updated_task_without_points_upon_completion_sums_up_its_subtasks(): void
    {
        $task = $this->createTask(40);
        $subtask = $this->createSubtask($task, 'Implement', 10);

        $this->putJson('/api/v1/tasks/'.$task->id, [
            'title' => $task->title,
            'points_upon_completion' => '',
            'subtasks' => [
                ['id' => $subtask->id, 'title' => 'Implement', 'points_upon_completion' => 12],
                ['title' => 'Review', 'points_upon_completion' => 8],
            ],
        ])->assertOk();

        $task->refresh();

        $this->assertSame(20, (int) $task->points_upon_completion);
    }

    public function test_a_task_without_subtask_points_stays_without_points_upon_completion(): void
    {
        $task = $this->createTask(0);

        $this->putJson('/api/v1/tasks/'.$task->id, [
            'title' => $task->title,
            'points_upon_completion' => '',
            'subtasks' => [
                ['title' => 'Implement', 'points_upon_completion' => 0],
            ],
        ])->assertOk();

        $task->refresh();

        $this->assertSame(0, (int) $task->points_upon_completion);
    }

    private function createTask(int $pointsUponCompletion): Task
    {
        return Task::create([
            'life_area_id' => $this->category->life_area_id,
            'category_id' => $this->category->id,
            'title' => 'Ticket',
            'points_upon_completion' => $pointsUponCompletion,
            'active' => 1,
        ]);
    }

    private function createSubtask(Task $task, string $title, int $pointsUponCompletion): Subtask
    {
        return Subtask::create([
            'life_area_id' => $task->life_area_id,
            'category_id' => $task->category_id,
            'task_id' => $task->id,
            'title' => $title,
            'points_upon_completion' => $pointsUponCompletion,
            'active' => 1,
        ]);
    }
}
