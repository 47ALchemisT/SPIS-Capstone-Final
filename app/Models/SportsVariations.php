<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportsVariations extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_sport_id',
        'sport_variation_name',
        'sport_variation_venue_id',
        'status',
        'date',
        'time'
    ];

    public function sport_id()
    {
        return $this->belongsTo(AssignedSports::class, 'assigned_sport_id');
    }

    public function venue_id()
    {
        return $this->belongsTo(Venue::class, 'sport_variation_venue_id');
    }

    public function sportVariationID()
    {
        return $this->hasMany(SportsVariationsMatches::class, 'sport_variation_id');
    }

    
    public function ffo_matchResult()
    {
        return $this->hasMany(FfoFacilitatorSubmits::class, 'match_id');
    }

}
