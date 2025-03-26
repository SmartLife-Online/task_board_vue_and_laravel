<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class User extends Model
{
    use ModelTrait;

    protected $table = 'users';
    protected $fillable = ['name', 'email'];
    protected $hidden = ['password', 'remember_token', 'email_verified_at'];
    
    public function lifeAreas()
    {
        return $this->hasMany(LifeArea::class);
    }

    public function recalcPoints() {
        $this->points = 0;

        foreach ($this->lifeAreas as $lifeArea) {
            $lifeArea->recalcPoints();

            $this->points += $lifeArea->points;
        }

        $this->points = $this->points * $this->points_multiplier_in_percent / 100;

        $this->update();
    }

}
