<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedTeams extends Model
{
    use HasFactory;

    protected $table = 'assigned_teams';

    protected $fillable = [
        'assigned_team_name',
        'college_id',
        'palakasan_id'
        
    ];

    // An assigned team belongs to one college
    public function college()
    {
        return $this->belongsTo(College::class, 'college_id');
    }
    public function palakasan()
    {
        return $this->belongsTo(Palakasan::class, 'palakasan_id');
    }

    // An assigned team can participate in many matches (TeamA or TeamB)
    public function matchesAsTeamA()
    {
        return $this->hasMany(SportMatch::class, 'teamA_id');
    }

    public function matchesAsTeamB()
    {
        return $this->hasMany(SportMatch::class, 'teamB_id');
    }

     // Added a method to get all matches for the team
     public function allMatches()
     {
         return $this->matchesAsTeamA()->union(
             $this->matchesAsTeamB()->toBase()
         );
     }
 
     public function comHeadColleges()
     {
         return $this->hasMany(ComHeadColleges::class, 'assigned_team_id');
     }

     public function student_players()
     {
         return $this->hasMany(StudentPlayer::class, 'assigned_team_id');
     }

     public function winning_team()
     {
         return $this->hasMany(MatchResult::class, 'winning_team_id');
     }
 
     public function losing_team()
     {
         return $this->hasMany(MatchResult::class, 'losing_team_id');
     }

     public function assignedTeamVariationID()
     {
         return $this->hasMany(SportsVariationsMatches::class, 'sport_variation_team_id');
     }

     public function team_id()
     {
         return $this->hasMany(TeamStanding::class, 'team_id');
     }

     public function assignedTeamID()
     {
         return $this->hasMany(OverallResult::class, 'assigned_team_id');
     }

     public function assigned_team()
     {
         return $this->hasMany(ComheadColleges::class, 'assigned_team_id');
     }

     public function matchResults()
     {
         return $this->hasMany(MatchResult::class, 'team_id', 'id');
     }
}
