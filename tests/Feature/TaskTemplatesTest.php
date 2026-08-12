<?php

namespace Tests\Feature;

use App\Category;
use App\LifeArea;
use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TaskTemplatesTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'test-password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $lifeArea = LifeArea::create(['title' => 'Work']);
        $this->category = Category::create([
            'life_area_id' => $lifeArea->id,
            'title' => 'Development',
        ]);
        $this->project = Project::create([
            'life_area_id' => $lifeArea->id,
            'category_id' => $this->category->id,
            'title' => 'Task Board',
        ]);
    }

    public function test_a_template_with_subtasks_can_be_created_and_updated(): void
    {
        $template = $this->postJson('/api/v1/task_templates', $this->templatePayload())
            ->assertCreated()
            ->assertJsonPath('name', 'Ticket workflow')
            ->assertJsonCount(2, 'subtasks')
            ->json();

        $updatedPayload = $this->templatePayload();
        $updatedPayload['name'] = 'Updated workflow';
        $updatedPayload['subtasks'] = [
            ['title' => 'Only step', 'description' => null, 'points_upon_completion' => 10],
        ];

        $this->putJson('/api/v1/task_templates/'.$template['id'], $updatedPayload)
            ->assertOk()
            ->assertJsonPath('name', 'Updated workflow')
            ->assertJsonCount(1, 'subtasks')
            ->assertJsonPath('subtasks.0.title', 'Only step');
    }

    public function test_a_template_can_create_multiple_completed_tasks_and_subtasks(): void
    {
        $templateId = $this->postJson('/api/v1/task_templates', $this->templatePayload())
            ->assertCreated()
            ->json('id');

        $this->postJson('/api/v1/task_templates/'.$templateId.'/instantiate', [
            'destination_type' => 'project',
            'destination_id' => $this->project->id,
            'tasks' => [
                ['title' => 'T-009'],
                ['title' => 'T-010'],
            ],
            'completed' => true,
        ])->assertCreated()
            ->assertJsonPath('created_count', 2)
            ->assertJsonPath('completed', true)
            ->assertJsonCount(2, 'tasks')
            ->assertJsonCount(2, 'tasks.0.subtasks');

        $tasks = Task::with('subtasks')->orderBy('id')->get();

        $this->assertCount(2, $tasks);
        $this->assertSame(['T-009', 'T-010'], $tasks->pluck('title')->all());
        $this->assertTrue($tasks->every(fn (Task $task) => $task->completed));
        $this->assertTrue($tasks->every(fn (Task $task) => $task->completed_at !== null));
        $this->assertTrue($tasks->flatMap->subtasks->every(fn ($subtask) => $subtask->completed));
        $this->assertTrue($tasks->flatMap->subtasks->every(fn ($subtask) => $subtask->completed_at !== null));
        $this->assertTrue($tasks->every(fn (Task $task) => $task->points === 100));
    }

    public function test_instantiation_uses_the_default_title_for_a_single_task(): void
    {
        $templateId = $this->postJson('/api/v1/task_templates', $this->templatePayload())
            ->assertCreated()
            ->json('id');

        $this->postJson('/api/v1/task_templates/'.$templateId.'/instantiate', [
            'destination_type' => 'category',
            'destination_id' => $this->category->id,
        ])->assertCreated()
            ->assertJsonPath('created_count', 1)
            ->assertJsonPath('tasks.0.title', 'Default ticket')
            ->assertJsonPath('tasks.0.project_id', null)
            ->assertJsonPath('tasks.0.completed', false);
    }

    private function templatePayload(): array
    {
        return [
            'name' => 'Ticket workflow',
            'task_title' => 'Default ticket',
            'task_description' => 'Implement the ticket',
            'task_points_upon_completion' => 40,
            'subtasks' => [
                ['title' => 'Implement', 'description' => null, 'points_upon_completion' => 25],
                ['title' => 'Review', 'description' => null, 'points_upon_completion' => 35],
            ],
        ];
    }
}
