<?php

namespace App;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    use ModelTrait;

    protected $fillable = [
        'user_id',
        'name',
        'task_title',
        'task_description',
        'task_points_upon_completion',
        'active',
    ];

    protected $casts = [
        'task_points_upon_completion' => 'integer',
        'active' => 'boolean',
    ];

    public function subtasks()
    {
        return $this->hasMany(TaskTemplateSubtask::class)->orderBy('sort_order');
    }
}
