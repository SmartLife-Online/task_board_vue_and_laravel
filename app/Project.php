<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Project extends Model
{
    use ModelTrait;

    public static function findActive(int $idProject)
    {
        $project = self::where('id', $idProject)->where('active', 1)->first();

        if(!$project) abort(response()->json(['message' => 'Project not found'], 404));

        return $project;
    }

    public static function allActive(?int $idCategory = null)
    {
        $query = self::where('active', 1);

        if ($idCategory) {
            $query = $query->where('category_id', $idCategory);
        }
        
        return $query->get();
    }

    public static function allNotComplted()
    {
        return self::where('active', 1)->where('completed', 0)->orderBy('life_area_id')->orderBy('category_id')->get();
    }

    public static function allComplted()
    {
        return self::where('active', 1)->where('completed', 1)->orderBy('life_area_id')->orderBy('category_id')->get();
    }

    public static function allDeleted()
    {
        return self::where('active', 0)->get();
    }

    public function lifeArea()
    {
        return $this->belongsTo(LifeArea::class)->where('active', 1);
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->where('active', 1);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->where('active', 1);
    }

    public function habits()
    {
        return $this->hasMany(Habit::class)->where('active', 1);
    }

    public static function allSortedBylifeAreaAndCategory() {
        return self::where('active', 1)->orderBy('life_area_id')->orderBy('category_id')->get();
    }
    
    public function recalcPoints() {
        $this->points = 0;

        foreach ($this->tasks as $task) {
            $task->recalcPoints();

            $this->points += $task->points;
        }

        foreach ($this->habits as $habit) {
            $this->points += $habit->getPoints();
        }

        if($this->completed) {
            $this->points += $this->points_upon_completion;
        }

        $this->points = $this->points * $this->points_multiplier_in_percent / 100;

        $this->update();
    }

}
