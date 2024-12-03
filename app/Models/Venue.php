<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'venues';

    protected $fillable = [
        'name',
        'description',
        'location',
        'capacity',
        'status'
    ];

    // Custom activity description for better logging
    protected function getActivityDescription($type)
    {
        switch ($type) {
            case 'create':
                return "added new venue: {$this->name}";
            case 'update':
                return "updated venue: {$this->name}";
            case 'delete':
                return "removed venue: {$this->name}";
            default:
                return parent::getActivityDescription($type);
        }
    }

    // Only log important changes
    protected function getActivityProperties()
    {
        return [
            'venue_name' => $this->name,
            'location' => $this->location,
            'status' => $this->status,
            'changes' => $this->getDirty()
        ];
    }

    public function matchVenue()
    {
        return $this->hasMany(SportMatch::class, 'match_venue_id'); 
    }

    public function venue_id()
    {
        return $this->hasMany(SportsVariations::class, 'sport_variation_venue_id'); 
    }

    public function venueID(){
        return $this->hasMany(UsedVenueRecord::class, 'venue_id');
    }
}
