<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Category extends Model
{
    use ModelTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    protected $fillable = ['user_id', 'life_area_id', 'title', 'description', 'points', 'points_multiplier_in_percent', 'active'];

    public static function findActive(int $idCategory)
    {
        $category = self::where('id', $idCategory)->where('active', 1)->first();

        if(!$category) abort(response()->json(['message' => 'Category not found'], 404));

        return $category;
    }

    public static function allActive(?int $idLifeArea = null)
    {
        $query = self::where('active', 1);

        if ($idLifeArea) {
            $query = $query->where('life_area_id', $idLifeArea);
        }
        
        return $query->get();
    }

    public static function allSortedBylifeArea() {
        return self::where('active', 1)->orderBy('life_area_id')->get();
    }

    public function lifeArea()
    {
        return $this->belongsTo(LifeArea::class)->where('active', 1);
    }

    public function projects()
    {
        return $this->hasMany(Project::class)->where('active', 1);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->where('active', 1);
    }

    public function tasksWithoutProject()
    {
        return $this->hasMany(Task::class)->where('active', 1)->whereNull('project_id');
    }

    public function habits()
    {
        return $this->hasMany(Habit::class)->where('active', 1);
    }

    public function habitsWithoutProject()
    {
        return $this->hasMany(Habit::class)->where('active', 1)->whereNull('project_id');
    }
    
    public function recalcPoints() {
        $this->points = 0;

        foreach ($this->projects as $project) {
            $project->recalcPoints();

            $this->points += $project->points;
        }

        foreach ($this->tasksWithoutProject as $task) {
            $task->recalcPoints();

            $this->points += $task->points;
        }

        foreach ($this->habitsWithoutProject as $habit) {
            $this->points += $habit->getPoints();
        }

        $this->points = $this->points * $this->points_multiplier_in_percent / 100;
        
        if($this->isDirty('points')) {
            $this->update();
        }
    }

}
