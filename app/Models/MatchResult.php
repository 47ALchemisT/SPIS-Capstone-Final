<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SportMatch; 

class MatchResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport_match_id',
        'teamA_score',
        'teamB_score',
        'winning_team_id',
        'losing_team_id',
        'assigned_sport_id',
    ];

    /**
     * Get the sport match that owns this result
     */
    public function sportMatch()
    {
        return $this->belongsTo(SportMatch::class, 'sport_match_id');
    }

    public function assignedSport()
    {
        return $this->belongsTo(AssignedSports::class, 'assigned_sport_id');
    }

    public function winning_team()
    {
        return $this->belongsTo(AssignedTeams::class, 'winning_team_id');
    }

    public function losing_team()
    {
        return $this->belongsTo(AssignedTeams::class, 'losing_team_id');
    }

    public function matchResult()
    {
        return $this->hasMany(FacilitatorSubmits::class, 'match_id');
    }
}