<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Project;
use App\Category;
use App\Habit;

class HabitController extends Controller
{

    public function index(): JsonResponse
    {
        return self::indexJSON(Habit::allSortedBylifeAreaAndCategory());
    }

    public function indexNotComplted(): JsonResponse
    {
        return self::indexJSON(Habit::allNotComplted());
    }

    public function indexComplted(): JsonResponse
    {
        return self::indexJSON(Habit::allComplted());
    }

    public function indexDeleted(): JsonResponse
    {
        return self::indexJSON(Habit::allDeleted());
    }

    public function indexJSON(Collection $habits): JsonResponse
    {
        $habitsJSON = [];
        
        foreach($habits as $habit) {
            $habitsJSON[] = [
                'id' => $habit->id,
                'life_area' => $habit->lifeArea->title ?? '',
                'life_area_id' => $habit->life_area_id ?? null,
                'category' => $habit->category->title ?? '',
                'category_id' => $habit->category_id ?? null,
                'project' => $habit->project->title ?? '',
                'project_id' => $habit->project_id ?? null,
                'title' => $habit->title,
                'description' => $habit->description,
                'points_per_completion' => $habit->points_per_completion,
                'count_completed' => $habit->count_completed,
                'points' => $habit->getPoints(),
                'points_upon_completion' => $habit->points_upon_completion,
                'completed' => $habit->completed,
                'active' => $habit->active,
            ];
        }

        return response()->json($habitsJSON);
    }

    public function get(int $idHabit): JsonResponse
    {
        $habit = Habit::findActive($idHabit);
        if(!$habit) {
            return response()->json(['error' => 'Habit not found'], 404);
        }

        return response()->json($habit);
    }

    public function storeToCategory(int $idCategory, Request $request): JsonResponse
    {
        $category = Category::findActive($idCategory);
        if(!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $habit = new Habit();
        
        $habit->life_area_id = $category->life_area_id;
        $habit->category_id = $category->id;
        $habit->project_id = null;
        $habit->title = $request->title;
        $habit->description = $request->description;
        $habit->points_per_completion = $request->points_per_completion;
        $habit->count_completed = 0;
        $habit->points_upon_completion = $request->points_upon_completion ?? 0;

        $habit->save();

        return response()->json($habit);
    }

    public function storeToProject(int $idProject, Request $request): JsonResponse
    {
        $project = Project::findActive($idProject);
        if(!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $habit = new Habit();
        
        $habit->life_area_id = $project->life_area_id;
        $habit->category_id = $project->category_id;
        $habit->project_id = $project->id;
        $habit->title = $request->title;
        $habit->description = $request->description;
        $habit->points_per_completion = $request->points_per_completion;
        $habit->count_completed = 0;
        $habit->points_upon_completion = $request->points_upon_completion ?? 0;

        $habit->save();

        return response()->json($habit);
    }

    public function update(int $idHabit, Request $request): JsonResponse
    {
        $habit = Habit::findActive($idHabit);
        if(!$habit) {
            return response()->json(['error' => 'Habit not found'], 404);
        }

        $habit->title = $request->title;
        $habit->description = $request->description;
        $habit->points_per_completion = $request->points_per_completion;
        $habit->points_upon_completion = $request->points_upon_completion ?? 0;

        $habit->update();

        return response()->json($habit);
    }

    public function countUpCompleted(int $idHabit, Request $request): JsonResponse
    {
        $habit = Habit::findActive($idHabit);
        if(!$habit) {
            return response()->json(['error' => 'Habit not found'], 404);
        }

        $habit->count_completed++;

        $habit->update();

        return response()->json(['success' => true, 'count_completed' => $habit->count_completed, 'points' => $habit->getPoints()]);
    }

    public function countDownCompleted(int $idHabit, Request $request): JsonResponse
    {
        $habit = Habit::findActive($idHabit);
        if(!$habit) {
            return response()->json(['error' => 'Habit not found'], 404);
        }

        $habit->count_completed--;
        
        if($habit->count_completed < 0) {
            $habit->count_completed = 0;
        }

        $habit->update();

        return response()->json(['success' => true, 'count_completed' => $habit->count_completed, 'points' => $habit->getPoints()]);
    }

    public function complete(int $idHabit, Request $request): JsonResponse
    {
        $habit = Habit::findActive($idHabit);
        if(!$habit) {
            return response()->json(['error' => 'Habit not found'], 404);
        }

        $habit->completed = 1;
        $habit->completed_at = now();

        $habit->update();

        return response()->json(['success' => true]);
    }

    public function delete(int $idHabit): JsonResponse
    {
        $habit = Habit::findActive($idHabit);
        if(!$habit) {
            return response()->json(['error' => 'Habit not found'], 404);
        }

        $habit->active = 0;

        $habit->update();

        return response()->json(['success' => true]);
    }
}
