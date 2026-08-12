<?php

namespace App\Http\Controllers;

use App\Category;
use App\Project;
use App\Task;
use App\TaskTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskTemplatesController extends Controller
{
    public function index(): JsonResponse
    {
        $templates = TaskTemplate::with('subtasks')
            ->where('user_id', $this->userId())
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return response()->json($templates);
    }

    public function get(int $idTaskTemplate): JsonResponse
    {
        return response()->json($this->findTemplate($idTaskTemplate)->load('subtasks'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateTemplate($request);

        $template = DB::transaction(function () use ($validated) {
            $template = TaskTemplate::create($this->templateAttributes($validated));
            $this->replaceSubtasks($template, $validated['subtasks']);

            return $template;
        });

        return response()->json($template->fresh('subtasks'), 201);
    }

    public function update(int $idTaskTemplate, Request $request): JsonResponse
    {
        $template = $this->findTemplate($idTaskTemplate);
        $validated = $this->validateTemplate($request);

        DB::transaction(function () use ($template, $validated) {
            $template->update($this->templateAttributes($validated));
            $this->replaceSubtasks($template, $validated['subtasks']);
        });

        return response()->json($template->fresh('subtasks'));
    }

    public function delete(int $idTaskTemplate): JsonResponse
    {
        $template = $this->findTemplate($idTaskTemplate);
        $template->update(['active' => false]);

        return response()->json(['success' => true]);
    }

    public function instantiate(int $idTaskTemplate, Request $request): JsonResponse
    {
        $template = $this->findTemplate($idTaskTemplate)->load('subtasks');
        $validated = $request->validate([
            'destination_type' => ['required', Rule::in(['category', 'project'])],
            'destination_id' => ['required', 'integer', 'min:1'],
            'tasks' => ['sometimes', 'array', 'min:1', 'max:200'],
            'tasks.*' => ['array'],
            'tasks.*.title' => ['nullable', 'string', 'max:255'],
            'tasks.*.description' => ['nullable', 'string'],
            'tasks.*.points_upon_completion' => ['nullable', 'integer', 'min:0'],
            'completed' => ['sometimes', 'boolean'],
            'day_schedule_part_id' => ['nullable', 'integer', 'exists:day_schedule_parts,id'],
        ]);

        $destination = $this->resolveDestination(
            $validated['destination_type'],
            (int) $validated['destination_id']
        );
        $tasksData = $validated['tasks'] ?? [['title' => $template->task_title]];
        $completed = (bool) ($validated['completed'] ?? false);
        $completedAt = $completed ? now() : null;

        $createdTasks = DB::transaction(function () use (
            $template,
            $destination,
            $tasksData,
            $validated,
            $completed,
            $completedAt
        ) {
            $createdTasks = collect();

            foreach ($tasksData as $taskData) {
                $title = trim((string) ($taskData['title'] ?? '')) ?: $template->task_title;

                $task = Task::create([
                    'life_area_id' => $destination['life_area_id'],
                    'category_id' => $destination['category_id'],
                    'project_id' => $destination['project_id'],
                    'day_schedule_part_id' => $validated['day_schedule_part_id'] ?? null,
                    'title' => $title,
                    'description' => $taskData['description'] ?? $template->task_description,
                    'points_upon_completion' => $taskData['points_upon_completion']
                        ?? $template->task_points_upon_completion,
                    'completed' => $completed,
                    'completed_at' => $completedAt,
                    'active' => true,
                ]);

                $task->createSubtasks($template->subtasks->map(fn ($subtask) => [
                    'title' => $subtask->title,
                    'description' => $subtask->description,
                    'points_upon_completion' => $subtask->points_upon_completion,
                    'completed' => $completed,
                ])->all());

                if ($completed) {
                    $task->recalcPoints();
                }

                $createdTasks->push($task->load('subtasks'));
            }

            return $createdTasks;
        });

        return response()->json([
            'created_count' => $createdTasks->count(),
            'completed' => $completed,
            'tasks' => $createdTasks,
        ], 201);
    }

    private function validateTemplate(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'task_title' => ['required', 'string', 'max:255'],
            'task_description' => ['nullable', 'string'],
            'task_points_upon_completion' => ['required', 'integer', 'min:0'],
            'subtasks' => ['required', 'array', 'min:1', 'max:100'],
            'subtasks.*' => ['array'],
            'subtasks.*.title' => ['required', 'string', 'max:255'],
            'subtasks.*.description' => ['nullable', 'string'],
            'subtasks.*.points_upon_completion' => ['required', 'integer', 'min:0'],
        ]);
    }

    private function templateAttributes(array $validated): array
    {
        return [
            'name' => trim($validated['name']),
            'task_title' => trim($validated['task_title']),
            'task_description' => $validated['task_description'] ?? null,
            'task_points_upon_completion' => $validated['task_points_upon_completion'],
            'active' => true,
        ];
    }

    private function replaceSubtasks(TaskTemplate $template, array $subtasks): void
    {
        $template->subtasks()->delete();

        foreach ($subtasks as $index => $subtask) {
            $template->subtasks()->create([
                'title' => trim($subtask['title']),
                'description' => $subtask['description'] ?? null,
                'points_upon_completion' => $subtask['points_upon_completion'],
                'sort_order' => $index,
            ]);
        }
    }

    private function resolveDestination(string $type, int $id): array
    {
        if ($type === 'project') {
            $project = Project::where('id', $id)
                ->where('active', true)
                ->where('completed', false)
                ->first();

            if (! $project) {
                abort(response()->json([
                    'message' => 'Active, incomplete project not found',
                ], 404));
            }

            return [
                'life_area_id' => $project->life_area_id,
                'category_id' => $project->category_id,
                'project_id' => $project->id,
            ];
        }

        $category = Category::findActive($id);

        return [
            'life_area_id' => $category->life_area_id,
            'category_id' => $category->id,
            'project_id' => null,
        ];
    }

    private function findTemplate(int $idTaskTemplate): TaskTemplate
    {
        return TaskTemplate::where('id', $idTaskTemplate)
            ->where('user_id', $this->userId())
            ->where('active', true)
            ->firstOrFail();
    }

    private function userId(): int
    {
        return Auth::id() ?? 1;
    }
}
