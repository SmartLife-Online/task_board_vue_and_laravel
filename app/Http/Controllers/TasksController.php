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
        return self::indexJSON(Task::all());
    }

    public function indexNotComplted(): JsonResponse
    {
        return self::indexJSON(Task::allNotComplted());
    }

    public function indexComplted(): JsonResponse
    {
        return self::indexJSON(Task::allComplted());
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
            ];
        }

        return response()->json($tasksJSON);
    }

    public function get(int $idTask): JsonResponse
    {
        $task = Task::find($idTask);

        return response()->json($task);
    }

    public function storeToCategory(int $idCategory, Request $request): JsonResponse
    {
        $category = Category::find($idCategory);
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
        $project = Project::find($idProject);
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
        $task = Task::find($idTask);

        $task->title = $request->title;
        $task->description = $request->description;
        $task->points_upon_completion = $request->points_upon_completion;

        $task->update();

        return response()->json($task);
    }

    public function complete(int $idTask, Request $request): JsonResponse
    {
        $task = Task::find($idTask);

        $task->completed = 1;
        $task->completed_at = now();

        $task->update();

        return response()->json(['success' => true]);
    }

}
