<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class DaySchedule extends Model
{
    use ModelTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    protected $fillable = ['day', 'user_id', 'title', 'description', 'points_upon_success', 'basis_points_at_start', 'basis_points_at_end', 'points_at_start', 'points_at_end', 'status_id', 'active'];

    public static function findActive(int $idDaySchedule)
    {
        $daySchedule = self::where('id', $idDaySchedule)->where('active', 1)->first();

        if(!$daySchedule) abort(response()->json(['message' => 'Day-Schedule not found'], 404));

        return $daySchedule;
    }

    public static function allActive()
    {
        return self::where('active', 1)->get();
    }

    public static function allPending()
    {
        return self::where('active', 1)->where('status_id', DS_STATUS_PENDING)->orderBy('day')->get();
    }

    public static function allInProgress()
    {
        return self::where('active', 1)->where('status_id', DS_STATUS_IN_PROGRESS)->orderBy('day')->get();
    }

    public static function allSuccessful()
    {
        return self::where('active', 1)->where('status_id', DS_STATUS_SUCCESSFUL)->orderBy('day')->get();
    }

    public static function allFailed()
    {
        return self::where('active', 1)->where('status_id', DS_STATUS_FAILED)->orderBy('day')->get();
    }

    public static function allDeleted()
    {
        return self::where('active', 0)->get();
    }

    public function dayScheduleParts()
    {
        return $this->hasMany(DaySchedulePart::class)->where('active', 1);
    }

    public static function getIdsdayScheduleParts(int $idDaySchedule)
    {
        return DaySchedulePart::allActive(idDaySchedule: $idDaySchedule, getQuery: true)->pluck('id');
    }

    public function getTasks() {
        $tasks = collect();
        
        foreach($this->dayScheduleParts as $dayScheduleParts) {
            if(!$dayScheduleParts->tasks) continue;
            
            foreach($dayScheduleParts->tasks as $task) {
                $tasks->push($task);
            }
        }

        return $tasks;
    }

    public function checkIfAllTasksAreCompleted() {
        foreach($this->getTasks() as $task) {
            if($task->completed) continue;

            return false;
        }

        return true;
    }
}
