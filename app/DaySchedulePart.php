<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class DaySchedulePart extends Model
{
    use ModelTrait;

    public static function findActive(int $idDaySchedulePart)
    {
        $daySchedulePart = self::where('id', $idDaySchedulePart)->where('active', 1)->first();

        if(!$daySchedulePart) abort(response()->json(['message' => 'Day-Schedule-Part not found'], 404));

        return $daySchedulePart;
    }

    public static function allActive($idDaySchedule = null, $getQuery = false)
    {
        $query = self::where('active', 1);

        if ($idDaySchedule) {
            $query = $query->where('day_schedule_id', $idDaySchedule);
        }

        if ($getQuery) {
            return $query;
        }

        return $query->get();
    }

    public static function allDeleted($idDaySchedule = null, $getQuery = false)
    {
        $query = self::where('active', 0);

        if ($idDaySchedule) {
            $query = $query->where('day_schedule_id', $idDaySchedule);
        }

        if ($getQuery) {
            return $query;
        }

        return $query->get();
    }

    public function daySchedule()
    {
        return $this->belongsTo(DaySchedule::class)->where('active', 1);
    }

    public function daySchedulePartTemplate()
    {
        return $this->belongsTo(DaySchedulePartTemplate::class)->where('active', 1);
    }
}
