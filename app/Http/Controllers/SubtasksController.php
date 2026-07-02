<?php

namespace App\Http\Controllers;

use App\Subtask;
use App\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SubtasksController extends Controller
{
    private function syncCompletedState(Subtask $subtask, bool $completed): void
    {
        $subtask->completed = $completed;

        if ($completed) {
            $subtask->completed_at = $subtask->completed_at ?? now();

            return;
        }

        $subtask->completed_at = null;
    }

    private function createSubtaskFromPayload(Task $task, array $subtaskData): ?Subtask
    {
        $title = trim((string) ($subtaskData['title'] ?? ''));

        if ($title === '') {
            return null;
        }

        $subtask = new Subtask;

        $subtask->life_area_id = $task->life_area_id;
        $subtask->category_id = $task->category_id;
        $subtask->project_id = $task->project_id;
        $subtask->task_id = $task->id;
        $subtask->title = $title;
        $subtask->description = trim((string) ($subtaskData['description'] ?? ''));
        $subtask->points_upon_completion = $subtaskData['points_upon_completion'] ?? 0;
        $subtask->day_schedule_part_id = $task->day_schedule_part_id;
        $this->syncCompletedState($subtask, filter_var($subtaskData['completed'] ?? false, FILTER_VALIDATE_BOOLEAN));

        $subtask->save();

        return $subtask;
    }

    public function index(?int $idTask = null): JsonResponse
    {
        return self::indexJSON(Subtask::allActive($idTask));
    }

    public function indexNotCompleted(?int $idTask = null): JsonResponse
    {
        return self::indexJSON(Subtask::allNotCompleted($idTask));
    }

    public function indexCompleted(?int $idTask = null): JsonResponse
    {
        return self::indexJSON(Subtask::allCompleted($idTask));
    }

    public function indexDeleted(?int $idTask = null): JsonResponse
    {
        return self::indexJSON(Subtask::allDeleted($idTask));
    }

    public function indexJSON(Collection $subtasks): JsonResponse
    {
        $subtasksJSON = [];

        foreach ($subtasks as $subtask) {
            $subtasksJSON[] = [
                'id' => $subtask->id,
                'life_area' => $subtask->lifeArea->title ?? '',
                'life_area_id' => $subtask->life_area_id ?? null,
                'category' => $subtask->category->title ?? '',
                'category_id' => $subtask->category_id ?? null,
                'project' => $subtask->project->title ?? '',
                'project_id' => $subtask->project_id ?? null,
                'task' => $subtask->task->title ?? '',
                'task_id' => $subtask->task_id,
                'title' => $subtask->title,
                'description' => $subtask->description,
                'points' => $subtask->points,
                'points_upon_completion' => $subtask->points_upon_completion,
                'completed' => $subtask->completed,
                'active' => $subtask->active,
            ];
        }

        return response()->json($subtasksJSON);
    }

    public function get(int $idSubtask): JsonResponse
    {
        $subtask = Subtask::findActive($idSubtask);
        if (! $subtask) {
            return response()->json(['error' => 'Subtask not found'], 404);
        }

        return response()->json($subtask);
    }

    public function store(int $idTask, Request $request): JsonResponse
    {
        $task = Task::findActive($idTask);
        if (! $task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        if (is_array($request->subtasks)) {
            $createdSubtasks = [];

            foreach ($request->subtasks as $subtaskData) {
                $subtask = $this->createSubtaskFromPayload($task, is_array($subtaskData) ? $subtaskData : []);

                if ($subtask) {
                    $createdSubtasks[] = $subtask;
                }
            }

            return response()->json($createdSubtasks);
        }

        $subtask = $this->createSubtaskFromPayload($task, $request->only([
            'title',
            'description',
            'points_upon_completion',
            'completed',
        ]));

        return response()->json($subtask);
    }

    public function update(int $idSubtask, Request $request): JsonResponse
    {
        $subtask = Subtask::findActive($idSubtask);
        if (! $subtask) {
            return response()->json(['error' => 'Subtask not found'], 404);
        }

        $subtask->title = $request->title;
        $subtask->description = $request->description;
        $subtask->points_upon_completion = $request->points_upon_completion;
        $this->syncCompletedState($subtask, $request->boolean('completed'));

        $subtask->update();

        return response()->json($subtask);
    }

    public function complete(int $idSubtask): JsonResponse
    {
        $subtask = Subtask::findActive($idSubtask);
        if (! $subtask) {
            return response()->json(['error' => 'Subtask not found'], 404);
        }

        $this->syncCompletedState($subtask, true);

        $subtask->update();

        return response()->json(['success' => true]);
    }

    public function delete(int $idSubtask): JsonResponse
    {
        $subtask = Subtask::findActive($idSubtask);
        if (! $subtask) {
            return response()->json(['error' => 'Subtask not found'], 404);
        }

        $subtask->active = 0;

        $subtask->update();

        return response()->json(['success' => true]);
    }
}
