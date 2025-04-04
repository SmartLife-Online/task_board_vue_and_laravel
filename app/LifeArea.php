<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class LifeArea extends Model
{
    use ModelTrait;

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public static function allSortedByTitle() {
        return self::orderBy('title')->get();
    }

    public static function allFromUser() {
        $userId = Auth::id() ?? 1;
        
        return self::where('user_id', $userId)->get();
    }
    
    public function recalcPoints() {
        $this->points = 0;

        foreach ($this->categories as $category) {
            $category->recalcPoints();

            $this->points += $category->points;
        }
        
        $this->points = $this->points * $this->points_multiplier_in_percent / 100;

        $this->update();
    }

}
