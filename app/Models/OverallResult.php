<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class OverallResult extends Model
{
    use HasFactory, LogsActivity;

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

    public function OverallResult()
    {
        return $this->hasMany(FacilitatorRankSubmitions::class, 'overall_result_id');
    }

    protected function getActivityDescription($type)
    {
        $teamName = $this->assignedTeamID ? $this->assignedTeamID->college->name : 'Unknown Team';
        $sportName = $this->assignedSportID ? $this->assignedSportID->sport->name : 'Unknown Sport';
        
        switch ($type) {
            case 'create':
                return "submitted Final ranking for {$teamName} in {$sportName}";
            case 'update':
                return "updated Final ranking for {$teamName} in {$sportName}";
            case 'delete':
                return "removed Final ranking for {$teamName} in {$sportName}";
            default:
                return parent::getActivityDescription($type);
        }
    }

    protected function getActivityProperties()
    {
        return [
            'assigned_sport_id' => $this->assigned_sport_id,
            'assigned_team_id' => $this->assigned_team_id,
            'rank' => $this->rank,
            'points' => $this->points,
            'changes' => $this->getDirty()
        ];
    }
}
