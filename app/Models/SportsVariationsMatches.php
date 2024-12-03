<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class SportsVariationsMatches extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'sport_variation_id',
        'sport_variation_team_id',
        'rank',
        'points'
    ];

    public function sportVariationID()
    {
        return $this->belongsTo(SportsVariations::class, 'sport_variation_id');
    }

    public function assignedTeamVariationID()
    {
        return $this->belongsTo(AssignedTeams::class, 'sport_variation_team_id');
    }

    protected function getActivityDescription($type)
    {
        $teamName = $this->assignedTeamVariationID?->college?->name ?? 'Unknown Team';
        $variationName = $this->sportVariationID?->sport_variation_name ?? 'Unknown Variation';
        $sportName = $this->sportVariationID?->assignedSport?->sport?->name ?? 'Unknown Sport';
        
        switch ($type) {
            case 'create':
                return "added {$teamName}'s rank in {$variationName} ({$sportName})";
            case 'update':
                return "updated {$teamName}'s rank in {$variationName} ({$sportName})";
            case 'delete':
                return "removed {$teamName}'s rank in {$variationName} ({$sportName})";
            default:
                return parent::getActivityDescription($type);
        }
    }

    protected function getActivityProperties()
    {
        return [
            'sport_variation_id' => $this->sport_variation_id,
            'sport_variation_team_id' => $this->sport_variation_team_id,
            'rank' => $this->rank,
            'points' => $this->points,
            'team_name' => $this->assignedTeamVariationID?->college?->name,
            'variation_name' => $this->sportVariationID?->sport_variation_name,
            'sport_name' => $this->sportVariationID?->assignedSport?->sport?->name,
            'changes' => $this->getDirty()
        ];
    }
}
