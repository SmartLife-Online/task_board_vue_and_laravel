<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class LifeArea extends Model
{
    use ModelTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    protected $fillable = ['user_id', 'title', 'description', 'points', 'points_multiplier_in_percent', 'active'];

    public static function findActive(int $idLifeArea)
    {
        $lifeArea = self::where('id', $idLifeArea)->where('active', 1)->first();

        if(!$lifeArea) abort(response()->json(['message' => 'Life Area not found'], 404));

        return $lifeArea;
    }

    public static function allActive()
    {
        return self::where('active', 1)->get();
    }

    public static function allSortedByTitle() {
        return self::where('active', 1)->orderBy('title')->get();
    }

    public static function allFromUser() {
        $userId = Auth::id() ?? 1;
        
        return self::where('active', 1)->where('user_id', $userId)->get();
    }

    public function categories()
    {
        return $this->hasMany(Category::class)->where('active', 1);
    }
    
    public function recalcPoints() {
        $this->points = 0;

        foreach ($this->categories as $category) {
            $category->recalcPoints();

            $this->points += $category->points;
        }
        
        $this->points = $this->points * $this->points_multiplier_in_percent / 100;

        if($this->isDirty('points')) {
            $this->update();
        }
    }

}
