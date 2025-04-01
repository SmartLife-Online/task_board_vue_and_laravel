<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\User;

class UsersController extends Controller
{

    public function get(int $idUser): JsonResponse
    {
        $user = User::find($idUser);

        return response()->json($user);
    }

    public function recalcUserPoints($idUser): JsonResponse{
        $user = User::find($idUser);
        
        $user->recalcPoints();
        
        return response()->json(['success' => true, 'points' => $user->points]);
    }

    public function recalcPoints() {
        $users = User::all();

        $totalPoints = 0;
        $timeStart = time();
        
        foreach ($users as $user) {
            $user->recalcPoints();

            $totalPoints += $user->points;
        }
        $timeEnd = time();
        
        return 'Points recalculated: ' . $totalPoints . ' <br>In ' . ($timeEnd - $timeStart) . ' seconds';
    }

}
