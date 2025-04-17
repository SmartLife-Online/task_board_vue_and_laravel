<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Habit extends Model
{
    use ModelTrait;

    public static function findActive(int $idHabit)
    {
        $habit = self::where('id', $idHabit)->where('active', 1)->first();

        if(!$habit) abort(response()->json(['message' => 'Habit not found'], 404));

        return $habit;
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
        return self::where('active', 1)->where('completed', 0)->orderBy('life_area_id')->orderBy('category_id')->get();
    }

    public static function allComplted()
    {
        return self::where('active', 1)->where('completed', 1)->orderBy('life_area_id')->orderBy('category_id')->get();
    }

    public static function allSortedBylifeAreaAndCategory() {
        return self::where('active', 1)->orderBy('life_area_id')->orderBy('category_id')->get();
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

    public function getPoints() {
        return $this->points_per_completion * $this->count_completed + ($this->completed ? $this->points_upon_completion : 0);
    }
}
