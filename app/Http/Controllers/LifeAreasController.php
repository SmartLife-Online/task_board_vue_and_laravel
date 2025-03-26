<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\LifeArea;

class LifeAreasController extends Controller
{
    public function index(): JsonResponse
    {
        $lifeAreas = LifeArea::all();

        $lifeAreasJSON = [];
        
        foreach($lifeAreas as $lifeArea) {
            $lifeAreasJSON[] = [
                'id' => $lifeArea->id,
                'title' => $lifeArea->title,
                'description' => $lifeArea->description,
                'points' => $lifeArea->points,
                'points_multiplier_in_percent' => $lifeArea->points_multiplier_in_percent,
            ];
        }

        return response()->json($lifeAreasJSON);
    }

    public function get(int $idLifeArea): JsonResponse
    {
        $lifeArea = LifeArea::find($idLifeArea);

        return response()->json($lifeArea);
    }

    public function update(int $idLifeArea, Request $request): JsonResponse
    {
        $lifeArea = LifeArea::find($idLifeArea);

        $lifeArea->title = $request->title;
        $lifeArea->description = $request->description;
        $lifeArea->points_multiplier_in_percent = $request->points_multiplier_in_percent;

        $lifeArea->update();

        return response()->json($lifeArea);
    }
}
