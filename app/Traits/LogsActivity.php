<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create');
        });

        static::updated(function ($model) {
            $model->logActivity('update');
        });

        static::deleted(function ($model) {
            $model->logActivity('delete');
        });
    }

    public function logActivity($type)
    {
        $description = $this->getActivityDescription($type);
        
        // Try to get the authenticated user from different guards
        $user = null;
        $guards = ['web', 'student'];
        
        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                $user = auth()->guard($guard)->user();
                break;
            }
        }

        ActivityLog::create([
            'description' => $description,
            'type' => $type,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'properties' => $this->getActivityProperties(),
            'causer_type' => $user ? get_class($user) : null,
            'causer_id' => $user ? $user->id : null,
        ]);
    }

    protected function getActivityDescription($type)
    {
        $modelName = class_basename($this);
        
        switch ($type) {
            case 'create':
                return "created a new {$modelName}";
            case 'update':
                return "updated {$modelName}";
            case 'delete':
                return "deleted {$modelName}";
            default:
                return "{$type} {$modelName}";
        }
    }

    protected function getActivityProperties()
    {
        return [
            'attributes' => $this->getAttributes(),
            'changes' => $this->getDirty(),
        ];
    }
}
