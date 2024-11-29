<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamColleges extends Model
{
    protected $fillable = [
        'team_id',
        'college_id'
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(AssignedTeams::class, 'team_id');
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id');
    }
}
