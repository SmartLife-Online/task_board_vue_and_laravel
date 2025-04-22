<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Category;
use App\LifeArea;

class CategoriesController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::allSortedBylifeArea();

        $categoriesJSON = [];
        
        foreach($categories as $category) {
            $categoriesJSON[] = [
                'id' => $category->id,
                'life_area' => $category->lifeArea->title ?? '',
                'life_area_id' => $category->life_area_id ?? null,
                'title' => $category->title,
                'description' => $category->description,
                'points' => $category->points,
                'points_multiplier_in_percent' => $category->points_multiplier_in_percent,
                'active' => $category->active,
            ];
        }

        return response()->json($categoriesJSON);
    }

    public function get(int $idCategory): JsonResponse
    {
        $category = Category::find($idCategory);

        return response()->json($category);
    }

    public function store(int $idLifeArea, Request $request): JsonResponse
    {
        $lifeArea = LifeArea::find($idLifeArea);
        if(!$lifeArea) {
            return response()->json(['error' => 'Life area not found'], 404);
        }

        $category = new Category();
        
        $category->life_area_id = $idLifeArea;
        $category->title = $request->title;
        $category->description = $request->description;
        $category->points_multiplier_in_percent = $request->points_multiplier_in_percent;

        $category->save();

        return response()->json($category);
    }

    public function update(int $idCategory, Request $request): JsonResponse
    {
        $category = Category::find($idCategory);
        if(!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->title = $request->title;
        $category->description = $request->description;
        $category->points_multiplier_in_percent = $request->points_multiplier_in_percent;

        $category->update();

        return response()->json($category);
    }

    public function delete(int $idCategory): JsonResponse
    {
        $category = Category::findActive($idCategory);
        if(!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->active = 0;

        $category->update();

        return response()->json(['success' => true]);
    }
}
