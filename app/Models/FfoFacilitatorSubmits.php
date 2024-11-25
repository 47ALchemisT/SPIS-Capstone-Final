<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FfoFacilitatorSubmits extends Model
{
    use HasFactory;

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
}
