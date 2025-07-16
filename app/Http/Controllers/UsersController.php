<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\User;
use App\DaySchedule;

class UsersController extends Controller
{

    public function get(int $idUser): JsonResponse
    {
        $user = User::find($idUser);

        $daySchedule = DaySchedule::where('user_id', $idUser)->where('status_id', DS_STATUS_IN_PROGRESS)->first();

        $user->day = $daySchedule->day ?? null;
        $user->day_id = $daySchedule->id ?? null;

        $user->setSeasonPoints();

        return response()->json($user);
    }

    public static function recalcUserPoints($idUser): JsonResponse{
        $user = User::find($idUser);
        
        $user->recalcPoints();
        
        return response()->json(['success' => true, 'points' => $user->points]);
    }

    public function recalcPoints() {
        $users = User::with('lifeAreas.categories.projects.tasks.subtasks', 'lifeAreas.categories.projects.habits', 'daySchedulesSuccessful')->get();

        $totalPoints = 0;
        $timeStart = microtime(true);
        
        foreach ($users as $user) {
            $user->recalcPoints();

            $totalPoints += $user->points;
        }
        $timeEnd = microtime(true);
        
        return 'Points recalculated: ' . $totalPoints . ' <br>In ' . ($timeEnd - $timeStart) . ' seconds';
    }

}
