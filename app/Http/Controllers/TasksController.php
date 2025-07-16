<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Task;
use App\Project;
use App\Category;
use App\DaySchedule;

class TasksController extends Controller
{

    public function index(?int $idProject = null): JsonResponse
    {
        return self::indexJSON(Task::allActive($idProject));
    }

    public function indexNotCompleted(?int $idProject = null): JsonResponse
    {
        return self::indexJSON(Task::allNotCompleted($idProject));
    }

    public function indexCompleted(?int $idProject = null): JsonResponse
    {
        return self::indexJSON(Task::allCompleted($idProject));
    }

    public function indexDeleted(?int $idProject = null): JsonResponse
    {
        return self::indexJSON(Task::allDeleted($idProject));
    }

    // Day-Schedule

    public function indexFromDaySchedule(?int $idDaySchedule): JsonResponse
    {
        return self::indexJSON(Task::allActivFromDaySchedule($idDaySchedule));
    }

    public function indexNotCompletedFromDaySchedule(?int $idDaySchedule): JsonResponse
    {
        return self::indexJSON(Task::allNotCompletedFromDaySchedule($idDaySchedule));
    }

    public function indexCompletedFromDaySchedule(?int $idDaySchedule): JsonResponse
    {
        return self::indexJSON(Task::allCompletedFromDaySchedule($idDaySchedule));
    }

    public function indexDeletedFromDaySchedule(?int $idDaySchedule): JsonResponse
    {
        return self::indexJSON(Task::allDeletedFromDaySchedule($idDaySchedule));
    }

    // Day-Schedule-Part

    public function indexFromDaySchedulePart(?int $idDaySchedulePart): JsonResponse
    {
        return self::indexJSON(Task::allActiveFromDaySchedulePart($idDaySchedulePart));
    }

    public function indexNotCompletedFromDaySchedulePart(?int $idDaySchedulePart): JsonResponse
    {
        return self::indexJSON(Task::allNotCompletedFromDaySchedulePart($idDaySchedulePart));
    }

    public function indexCompletedFromDaySchedulePart(?int $idDaySchedulePart): JsonResponse
    {
        return self::indexJSON(Task::allCompletedFromDaySchedulePart($idDaySchedulePart));
    }

    public function indexDeletedFromDaySchedulePart(?int $idDaySchedulePart): JsonResponse
    {
        return self::indexJSON(Task::allDeletedFromDaySchedulePart($idDaySchedulePart));
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
        $task->day_schedule_part_id = $request->day_schedule_part_id ?? null;

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
        $task->day_schedule_part_id = $request->day_schedule_part_id ?? null;

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
        $task->day_schedule_part_id = $request->day_schedule_part_id ?: null;

        $task->update();

        if($task->wasChanged('day_schedule_part_id')) {  
            foreach ($task->subtasks as $subtask) {
                $subtask->day_schedule_part_id = $task->day_schedule_part_id;

                $subtask->update();
            }
        }

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
