<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStanding extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'assigned_sport_id',
        'team_id',
        'played',
        'won',
        'drawn',
        'lost',
        'points'
    ];

    public function sport_id()
    {
        return $this->belongsTo(AssignedSports::class, 'assigned_sport_id');
    }
    
    public function teamID()
    {
        return $this->belongsTo(AssignedTeams::class, 'team_id');
    }

    
}
