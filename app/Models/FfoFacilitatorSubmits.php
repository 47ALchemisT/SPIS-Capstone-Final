<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FfoFacilitatorSubmits extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'facilitator_id',
        'match_id',
        'official_name',
        'signature'
    ];

    public function facilitator()
    {
        return $this->belongsTo(StudentAccount::class, 'facilitator_id');
    }

    public function match()
    {
        return $this->belongsTo(SportsVariations::class, 'match_id');
    }

    protected function getActivityDescription($type)
    {
        $facilitatorName = $this->facilitator?->student?->first_name . ' ' . $this->facilitator?->student?->last_name ?? 'Unknown Facilitator';
        $matchName = $this->match?->sport_variation_name ?? 'Unknown Match';
        
        switch ($type) {
            case 'create':
                return "submitted Free For All match result for {$matchName}";
            case 'update':
                return "updated Free For All match result for {$matchName}";
            case 'delete':
                return "removed Free For All match result for {$matchName}";
            default:
                return parent::getActivityDescription($type);
        }
    }

    protected function getActivityProperties()
    {
        return [
            'facilitator_id' => $this->facilitator_id,
            'match_id' => $this->match_id,
            'official_name' => $this->official_name,
            'facilitator_name' => $this->facilitator?->student?->first_name . ' ' . $this->facilitator?->student?->last_name,
            'match_name' => $this->match?->sport_variation_name,
            'changes' => $this->getDirty()
        ];
    }
}
