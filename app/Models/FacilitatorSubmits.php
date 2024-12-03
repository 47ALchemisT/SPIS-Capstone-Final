<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentAccount; 

class FacilitatorSubmits extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'facilitator_id',
        'match_id',
        'official_name',
        'signature',
    ];

    // Custom activity description for better logging
    protected function getActivityDescription($type)
    {
        $facilitator = $this->facilitator?->student;
        $facilitatorName = $facilitator 
            ? "{$facilitator->first_name} {$facilitator->last_name}"
            : $this->official_name;

        switch ($type) {
            case 'create':
                return " submitted match result, you can check the logs for more details";
            case 'update':
                return "updated submission by facilitator {$facilitatorName}";
            case 'delete':
                return "removed submission by facilitator {$facilitatorName}";
            default:
                return parent::getActivityDescription($type);
        }
    }

    // Only log important changes
    protected function getActivityProperties()
    {
        return [
            'facilitator_id' => $this->facilitator_id,
            'match_id' => $this->match_id,
            'official_name' => $this->official_name,
            'changes' => $this->getDirty()
        ];
    }

    public function facilitator()
    {
        return $this->belongsTo(StudentAccount::class, 'facilitator_id');
    }

    public function matchResult()
    {
        return $this->belongsTo(MatchResult::class, 'match_id');
    }
}
