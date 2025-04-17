<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Project;
use App\Category;

class ProjectsController extends Controller
{

    public function index(): JsonResponse
    {
        return self::indexJSON(Project::allSortedBylifeAreaAndCategory());
    }

    public function indexNotComplted(): JsonResponse
    {
        return self::indexJSON(Project::allNotComplted());
    }

    public function indexComplted(): JsonResponse
    {
        return self::indexJSON(Project::allComplted());
    }

    public function indexJSON(Collection $projects): JsonResponse
    {
        $projectsJSON = [];
        
        foreach($projects as $project) {
            $projectsJSON[] = [
                'id' => $project->id,
                'life_area' => $project->lifeArea->title ?? '',
                'life_area_id' => $project->life_area_id ?? null,
                'category' => $project->category->title ?? '',
                'category_id' => $project->category_id ?? null,
                'title' => $project->title,
                'description' => $project->description,
                'points' => $project->points,
                'points_upon_completion' => $project->points_upon_completion,
                'completed' => $project->completed,
                'points_multiplier_in_percent' => $project->points_multiplier_in_percent,
            ];
        }

        return response()->json($projectsJSON);
    }

    public function get(int $idProject): JsonResponse
    {
        $project = Project::findActive($idProject);
        if(!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        return response()->json($project);
    }

    public function store(int $idCategory, Request $request): JsonResponse
    {
        $category = Category::findActive($idCategory);
        if(!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $project = new Project();
        
        $project->life_area_id = $category->life_area_id;
        $project->category_id = $category->id;
        $project->title = $request->title;
        $project->description = $request->description;
        $project->points_multiplier_in_percent = $request->points_multiplier_in_percent ?? 100;
        $project->points_upon_completion = $request->points_upon_completion ?? 0;

        $project->save();

        return response()->json($project);
    }

    public function update(int $idProject, Request $request): JsonResponse
    {
        $project = Project::findActive($idProject);
        if(!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $project->title = $request->title;
        $project->description = $request->description;
        $project->points_multiplier_in_percent = $request->points_multiplier_in_percent ?? 100;
        $project->points_upon_completion = $request->points_upon_completion ?? 0;

        $project->update();

        return response()->json($project);
    }

    public function complete(int $idProject, Request $request): JsonResponse
    {
        $project = Project::findActive($idProject);
        if(!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $project->completed = 1;
        $project->completed_at = now();

        $project->update();

        return response()->json(['success' => true]);
    }
}
