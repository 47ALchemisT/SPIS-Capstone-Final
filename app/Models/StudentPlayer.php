<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPlayer extends Model
{
    use HasFactory;

    protected $table = 'student_players';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'student_assigned_sport_id',
        'assigned_team_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function assignedSport()
    {
        return $this->belongsTo(AssignedSports::class, 'student_assigned_sport_id');
    }

    public function assignedTeam()
    {
        return $this->belongsTo(AssignedTeams::class, 'assigned_team_id');
    }
}
