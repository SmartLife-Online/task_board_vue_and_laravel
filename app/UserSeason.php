<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class UserSeason extends Model
{
    use ModelTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    protected $fillable = ['user_id', 'season_id', 'points', 'basis_points'];
}
