<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Subtask extends Model
{
    use ModelTrait;

    public static function allNotComplted(?int $idTask = null)
    {
        if ($idTask) {
            return self::where('completed', 0)->where('task_id', $idTask)->get();
        }
        
        return self::where('completed', 0)->get();
    }

    public static function allComplted(?int $idTask = null)
    {
        if ($idTask) {
            return self::where('completed', 1)->where('task_id', $idTask)->get();
        }
        
        return self::where('completed', 1)->get();
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
