<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Subtask extends Model
{
    use ModelTrait;

    public static function findActive(int $idSubtask)
    {
        $subtask = self::where('id', $idSubtask)->where('active', 1)->first();

        if(!$subtask) abort(response()->json(['message' => 'Subtask not found'], 404));

        return $subtask;
    }

    public static function allActive(?int $idTask = null)
    {
        $query = self::where('active', 1);

        if ($idTask) {
            $query = $query->where('task_id', $idTask);
        }
        
        return $query->get();
    }

    public static function allNotComplted(?int $idTask = null)
    {
        $query = self::where('completed', 0)->where('active', 1);

        if ($idTask) {
            $query = $query->where('task_id', $idTask);
        }
        
        return $query->get();
    }

    public static function allComplted(?int $idTask = null)
    {
        $query = self::where('completed', 1)->where('active', 1);

        if ($idTask) {
            $query = $query->where('task_id', $idTask);
        }
        
        return $query->get();
    }

    public function lifeArea()
    {
        return $this->belongsTo(LifeArea::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public static function allSortedByTask() {
        return self::orderBy('task_id')->get();
    }

}
