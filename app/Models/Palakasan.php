<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Palakasan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'palakasans';

    protected $fillable = [
        'year',
        'theme',
        'tagline',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    // Custom activity description for better logging
    protected function getActivityDescription($type)
    {
        switch ($type) {
            case 'create':
                return "created new Palakasan event: {$this->year}";
            case 'update':
                $statusChange = $this->isDirty('status') ? " (Status: {$this->status})" : "";
                return "updated Palakasan event: {$this->year}{$statusChange}";
            case 'delete':
                return "removed Palakasan event: {$this->year}";
            default:
                return parent::getActivityDescription($type);
        }
    }

    // Only log important changes
    protected function getActivityProperties()
    {
        return [
            'event_year' => $this->year,
            'status' => $this->status,
            'date_range' => [
                'start' => $this->start_date->format('Y-m-d'),
                'end' => $this->end_date->format('Y-m-d')
            ],
            'changes' => $this->getDirty()
        ];
    }

    public function assignedTeams()
    {
        return $this->hasMany(AssignedTeams::class, 'palakasan_id');
    }

    public function assignedSports()
    {
        return $this->hasMany(AssignedSports::class, 'palakasan_sport_id');
    }

    public function palakasanID(){
        return $this->hasMany(UsedVenueRecord::class, 'palakasan_id');
    }
}
