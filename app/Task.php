<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Task extends Model
{
    use ModelTrait;

    public static function findActive(int $idTask)
    {
        $task = self::where('id', $idTask)->where('active', 1)->first();

        if(!$task) abort(response()->json(['message' => 'Task not found'], 404));

        return $task;
    }

    public static function allActive(?int $idProject = null)
    {
        $query = self::where('active', 1);

        if ($idProject) {
            $query = $query->where('project_id', $idProject);
        }
        
        return $query->get();
    }

    public static function allNotComplted()
    {
        return self::where('completed', 0)->get();
    }

    public static function allComplted()
    {
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

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public static function allSortedBylifeAreaAndCategoryAndProject() {
        return self::orderBy('life_area_id')->orderBy('category_id')->orderBy('project_id')->get();
    }
    
    public function recalcPoints() {
        $this->points = 0;

        foreach ($this->subtasks as $subtask) {
            if(!$subtask->completed) continue;

            $this->points += $subtask->points_upon_completion;
        }

        if($this->completed) {
            $this->points += $this->points_upon_completion;
        }

        $this->update();

        return $this->points;
    }

}
