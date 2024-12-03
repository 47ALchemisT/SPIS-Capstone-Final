<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FacilitatorRankSubmitions extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'facilitator_rank_submitions';

    protected $fillable = [
        'overall_result_id',
        'facilitator_id',
    ];

    public function overallResult()
    {
        return $this->belongsTo(OverallResult::class, 'overall_result_id');
    }

    public function facilitator()
    {
        return $this->belongsTo(StudentAccount::class, 'facilitator_id');
    }

    protected function getActivityDescription($type)
    {
        switch ($type) {
            case 'create':
                return "submitted overall ranking result";
            case 'update':
                return "updated overall ranking submission";
            case 'delete':
                return "deleted overall ranking submission";
            default:
                return parent::getActivityDescription($type);
        }
    }

    protected function getActivityProperties()
    {
        return [
            'overall_result_id' => $this->overall_result_id,
            'facilitator_id' => $this->facilitator_id,
            'changes' => $this->getDirty()
        ];
    }
}
