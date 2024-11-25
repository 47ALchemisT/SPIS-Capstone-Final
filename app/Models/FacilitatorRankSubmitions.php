<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilitatorRankSubmitions extends Model
{
    use HasFactory;

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
}
