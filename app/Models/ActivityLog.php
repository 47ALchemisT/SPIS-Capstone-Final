<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentAccount;
use App\Models\Admin;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'type', // 'create', 'update', 'delete'
        'model_type', // e.g., 'Sport', 'College', 'Student'
        'model_id',
        'properties', // JSON field to store additional data
        'causer_type', // usually 'App\Models\StudentAccount'
        'causer_id'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function causer()
    {
        if ($this->causer_type === 'App\\Models\\Admin') {
            return $this->belongsTo(Admin::class, 'causer_id');
        }
        return $this->belongsTo(StudentAccount::class, 'causer_id');
    }
}
