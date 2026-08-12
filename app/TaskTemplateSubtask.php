<?php

namespace App;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class TaskTemplateSubtask extends Model
{
    use ModelTrait;

    protected $fillable = [
        'user_id',
        'task_template_id',
        'title',
        'description',
        'points_upon_completion',
        'sort_order',
    ];

    protected $casts = [
        'points_upon_completion' => 'integer',
        'sort_order' => 'integer',
    ];

    public function taskTemplate()
    {
        return $this->belongsTo(TaskTemplate::class);
    }
}
