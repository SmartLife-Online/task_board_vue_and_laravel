<?php

namespace App\Http\Controllers;

use App\LifeArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LifeAreasController extends Controller
{
    public function index(): JsonResponse
    {
        $lifeAreas = LifeArea::allActive();

        $lifeAreasJSON = [];

        foreach ($lifeAreas as $lifeArea) {
            $lifeAreasJSON[] = [
                'id' => $lifeArea->id,
                'title' => $lifeArea->title,
                'description' => $lifeArea->description,
                'basis_points' => $lifeArea->basis_points,
                'points' => $lifeArea->points,
                'points_multiplier_in_percent' => $lifeArea->points_multiplier_in_percent,
                'active' => $lifeArea->active,
            ];
        }

        return response()->json($lifeAreasJSON);
    }

    public function get(int $idLifeArea): JsonResponse
    {
        $lifeArea = LifeArea::findActive($idLifeArea);
        if (! $lifeArea) {
            return response()->json(['error' => 'Life-Area not found'], 404);
        }

        return response()->json($lifeArea);
    }

    public function update(int $idLifeArea, Request $request): JsonResponse
    {
        $lifeArea = LifeArea::findActive($idLifeArea);
        if (! $lifeArea) {
            return response()->json(['error' => 'Life-Area not found'], 404);
        }

        $lifeArea->title = $request->title;
        $lifeArea->description = $request->description;
        $lifeArea->points_multiplier_in_percent = $request->points_multiplier_in_percent;

        $lifeArea->update();

        return response()->json($lifeArea);
    }

    public function delete(int $idLifeArea): JsonResponse
    {
        $lifeArea = LifeArea::findActive($idLifeArea);
        if (! $lifeArea) {
            return response()->json(['error' => 'Life-Area not found'], 404);
        }

        $lifeArea->active = 0;

        $lifeArea->update();

        return response()->json(['success' => true]);
    }
}
