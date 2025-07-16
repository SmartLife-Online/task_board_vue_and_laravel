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

    public static function allNotCompleted(?int $idTask = null)
    {
        $query = self::where('active', 1)->where('completed', 0);

        if ($idTask) {
            $query = $query->where('task_id', $idTask);
        }
        
        return $query->get();
    }

    public static function allCompleted(?int $idTask = null)
    {
        $query = self::where('active', 1)->where('completed', 1);

        if ($idTask) {
            $query = $query->where('task_id', $idTask);
        }
        
        return $query->get();
    }

    public static function allDeleted(?int $idTask = null)
    {
        $query = self::where('active', 0);

        if ($idTask) {
            $query = $query->where('task_id', $idTask);
        }
        
        return $query->get();
    }

    public static function allSortedByTask() {
        return self::where('active', 1)->orderBy('task_id')->get();
    }

    public function lifeArea()
    {
        return $this->belongsTo(LifeArea::class)->where('active', 1);
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->where('active', 1);
    }

    public function project()
    {
        return $this->belongsTo(Project::class)->where('active', 1);
    }

    public function task()
    {
        return $this->belongsTo(Task::class)->where('active', 1);
    }

}
