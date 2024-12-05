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

    public function facilitator()
    {
        return $this->belongsTo(StudentAccount::class, 'facilitator_id');
    }

    public function matchResult()
    {
        return $this->belongsTo(MatchResult::class, 'match_id');
    }
}
