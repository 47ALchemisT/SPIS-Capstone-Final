<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedVenueRecord extends Model
{
    use HasFactory;

    protected $table = 'used_venue_records';

    protected $fillable = [
        'palakasan_id',
        'venue_id', 
        'date',
        'time'
    ];

    public function palakasanID(){
        return $this->belongsTo(Palakasan::class, 'palakasan_id');
    }

    public function venueID(){
        return $this->belongsTo(Venue::class, 'venue_id');
    }
}
