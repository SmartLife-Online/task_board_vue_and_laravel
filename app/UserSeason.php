<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class UserSeason extends Model
{
    use ModelTrait;

    protected $table = 'user_seasons';
}
