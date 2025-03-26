<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait ModelTrait
{
    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $userId = Auth::id() ?? 1;
            
            $model->user_id = $userId;
            $model->created_by = $userId;
            $model->updated_by = $userId;
        });

        static::updating(function ($model) {
            $userId = Auth::id() ?? 1;
            
            $model->updated_by = $userId;
        });
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}