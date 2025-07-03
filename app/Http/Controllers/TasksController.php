<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Task;
use App\Project;
use App\Category;

class TasksController extends Controller
{

    public function index(): JsonResponse
    {
        return self::indexJSON(Task::allActive());
    }

    public function indexNotComplted(): JsonResponse
    {
        return self::indexJSON(Task::allNotComplted());
    }

    public function indexComplted(): JsonResponse
    {
        return self::indexJSON(Task::allComplted());
    }

    public function indexDeleted(): JsonResponse
    {
        return self::indexJSON(Task::allDeleted());
    }

    public function indexJSON(Collection $tasks): JsonResponse
    {
        $tasksJSON = [];
        
        foreach($tasks as $task) {
            $tasksJSON[] = [
                'id' => $task->id,
                'life_area' => $task->lifeArea->title ?? '',
                'life_area_id' => $task->life_area_id ?? null,
                'category' => $task->category->title ?? '',
                'category_id' => $task->category_id ?? null,
                'project' => $task->project->title ?? '',
                'project_id' => $task->project_id ?? null,
                'title' => $task->title,
                'description' => $task->description,
                'points' => $task->points,
                'points_upon_completion' => $task->points_upon_completion,
                'completed' => $task->completed,
                'active' => $task->active,
            ];
        }

        return response()->json($tasksJSON);
    }

    public function get(int $idTask): JsonResponse
    {
        $task = Task::findActive($idTask);

        return response()->json($task);
    }

    public function storeToCategory(int $idCategory, Request $request): JsonResponse
    {
        $category = Category::findActive($idCategory);
        if(!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $task = new Task();
        
        $task->life_area_id = $category->life_area_id;
        $task->category_id = $category->id;
        $task->project_id = null;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->points_upon_completion = $request->points_upon_completion;

        $task->save();

        return response()->json($task);
    }

    public function storeToProject(int $idProject, Request $request): JsonResponse
    {
        $project = Project::findActive($idProject);
        if(!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $task = new Task();
        
        $task->life_area_id = $project->life_area_id;
        $task->category_id = $project->category_id;
        $task->project_id = $project->id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->points_upon_completion = $request->points_upon_completion;

        $task->save();

        return response()->json($task);
    }

    public function update(int $idTask, Request $request): JsonResponse
    {
        $task = Task::findActive($idTask);
        if(!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->title = $request->title;
        $task->description = $request->description;
        $task->points_upon_completion = $request->points_upon_completion;

        $task->update();

        return response()->json($task);
    }

    public function complete(int $idTask, Request $request): JsonResponse
    {
        $task = Task::findActive($idTask);
        if(!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->completed = 1;
        $task->completed_at = now();

        $task->update();

        return response()->json(['success' => true]);
    }

    public function recalcTask(int $idTask, Request $request): JsonResponse
    {
        $task = Task::findActive($idTask);
        if(!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json(['points' => $task->recalcPoints()]);
    }

    public function delete(int $idTask): JsonResponse
    {
        $task = Task::findActive($idTask);
        if(!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->active = 0;

        $task->update();

        return response()->json(['success' => true]);
    }

}
