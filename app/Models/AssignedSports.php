<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedSports extends Model
{
    use HasFactory;

    protected $table = 'assigned_sports';

    protected $fillable = [
        'palakasan_sport_id',
        'sport_id',
        'categories',
        'setup',
        'type',
        'facilitator_id',
        'status'
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }
    
    public function palakasan()
    {
        return $this->belongsTo(Palakasan::class, 'palakasan_sport_id');
    }

    public function sportMatches()
    {
        return $this->hasMany(SportMatch::class, 'assigned_sport_id');
    }

    public function studentsPlayers()
    {
        return $this->hasMany(StudentPlayer::class, 'student_assigned_sport_id');
    }
    public function sportVariations()
    {
        return $this->hasMany(SportsVariations::class, 'assigned_sport_id');
    }

    public function sport_id()
    {
        return $this->hasMany(TeamStanding::class, 'assigned_sport_id');
    }

    public function assignedSportID()
    {
        return $this->hasMany(OverallResult::class, 'assigned_sport_id');
    }

    public function facilitatorID(){
        return $this->belongsTo(StudentAccount::class, 'facilitator_id');
    }
}
