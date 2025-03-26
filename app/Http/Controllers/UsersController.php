<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{

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
