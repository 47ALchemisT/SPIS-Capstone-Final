<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_sport_id',
        'assigned_team_id',
        'rank',
        'points',
    ];

    public function assignedSportID()
    {
        return $this->belongsTo(AssignedSports::class, 'assigned_sport_id');
    }

    public function assignedTeamID()
    {
        return $this->belongsTo(AssignedTeams::class, 'assigned_team_id');
    }
    
}
