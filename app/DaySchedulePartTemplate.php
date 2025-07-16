<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class DaySchedulePartTemplate extends Model
{
    use ModelTrait;

    public static function findActive(int $idDaySchedulePartTemplate)
    {
        $daySchedulePartTemplate = self::where('id', $idDaySchedulePartTemplate)->where('active', 1)->first();

        if(!$daySchedulePartTemplate) abort(response()->json(['message' => 'Day-Schedule-Part-Template not found'], 404));

        return $daySchedulePartTemplate;
    }

    public static function allActive()
    {
        return self::where('active', 1)->get();
    }

    public static function allDeleted()
    {
        return self::where('active', 0)->get();
    }

}
