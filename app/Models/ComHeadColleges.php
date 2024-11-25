<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComHeadColleges extends Model
{
    use HasFactory;

    protected $table = 'com_head_colleges';

    protected $fillable = [
        'com_head_id',
        'assigned_team_id',
        'palakasan_sport_id'
    ];

    public function com_head()
    {
        return $this->belongsTo(StudentAccount::class, 'com_head_id');
    }

    public function assigned_team()
    {
        return $this->belongsTo(AssignedTeams::class, 'assigned_team_id');
    }

    public function palakasan()
    {
        return $this->belongsTo(Palakasan::class, 'palakasan_sport_id');
    }
}
