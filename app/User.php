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
        return $this->hasMany(LifeArea::class)->where('active', 1);
    }
    
    public function seasons()
    {
        return $this->hasMany(UserSeason::class, 'user_id', 'id');
    }
    
    public function currentSeason()
    {
        return $this->hasOne(UserSeason::class, 'user_id', 'id')->where('season_id', env('SEASON_ID', 0));
    }

    public function setSeasonPoints()
    {
        $this->seasonBasisPoints = $this->calcBasisSeasonPoints();
        $this->seasonPoints = $this->calcSeasonPoints();
    }

    public function calcBasisSeasonPoints()
    {
        return $this->basis_points - $this->currentSeason->basis_points;
    }

    public function calcSeasonPoints()
    {
        return (int) ($this->seasonBasisPoints * $this->points_multiplier_in_percent / 100);
    }

    public function recalcPoints() {
        $this->basis_points = 0;

        foreach ($this->lifeAreas as $lifeArea) {
            $lifeArea->recalcPoints();

            $this->basis_points += $lifeArea->points;
        }

        $this->points = $this->basis_points * $this->points_multiplier_in_percent / 100;

        $this->update();
    }

}
