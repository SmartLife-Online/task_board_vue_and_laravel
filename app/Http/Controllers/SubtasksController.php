<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Subtask;
use App\Task;

class SubtasksController extends Controller
{
    public function index(): JsonResponse
    {
        return self::indexJSON(Subtask::all());
    }

    public function indexNotComplted(?int $idTask = null): JsonResponse
    {
        return self::indexJSON(Subtask::allNotComplted($idTask));
    }

    public function indexComplted(?int $idTask = null): JsonResponse
    {
        return self::indexJSON(Subtask::allComplted($idTask));
    }

    public function indexJSON(Collection $subtasks): JsonResponse
    {
        $subtasksJSON = [];

        foreach($subtasks as $subtask) {
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
            ];
        }

        return response()->json($subtasksJSON);
    }

    public function get(int $idSubtask): JsonResponse
    {
        $subtask = Subtask::find($idSubtask);

        return response()->json($subtask);
    }

    public function store(int $idTask, Request $request): JsonResponse
    {
        $task = Task::find($idTask);
        if(!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $subtask = new Subtask();
        
        $subtask->life_area_id = $task->life_area_id;
        $subtask->category_id = $task->category_id;
        $subtask->project_id = $task->project_id;
        $subtask->task_id = $task->id;
        $subtask->title = $request->title;
        $subtask->description = $request->description;
        $subtask->points_upon_completion = $request->points_upon_completion;

        $subtask->save();

        return response()->json($subtask);
    }

    public function update(int $idSubtask, Request $request): JsonResponse
    {
        $subtask = Subtask::find($idSubtask);

        $subtask->title = $request->title;
        $subtask->description = $request->description;
        $subtask->points_upon_completion = $request->points_upon_completion;

        $subtask->update();

        return response()->json($subtask);
    }

    public function complete(int $idSubtask, Request $request): JsonResponse
    {
        $subtask = Subtask::find($idSubtask);

        $subtask->completed = 1;
        $subtask->completed_at = now();

        $subtask->update();

        return response()->json(['success' => true]);
    }
}
