<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Project extends Model
{
    use ModelTrait;

    public static function allNotComplted()
    {
        return self::where('completed', 0)->orderBy('life_area_id')->orderBy('category_id')->get();
    }

    public static function allComplted()
    {
        return self::where('completed', 1)->orderBy('life_area_id')->orderBy('category_id')->get();
    }

    public function lifeArea()
    {
        return $this->belongsTo(LifeArea::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    public static function allSortedBylifeAreaAndCategory() {
        return self::orderBy('life_area_id')->orderBy('category_id')->get();
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
