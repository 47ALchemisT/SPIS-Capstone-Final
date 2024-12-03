<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sports';

    protected $fillable = [
        'name',
        'description',
    ];


    // Custom activity description for better logging
    protected function getActivityDescription($type)
    {
        switch ($type) {
            case 'create':
                return "added new sport: {$this->name}";
            case 'update':
                return "updated sport: {$this->name}";
            case 'delete':
                return "removed sport: {$this->name}";
            default:
                return parent::getActivityDescription($type);
        }
    }

    // Only log important changes
    protected function getActivityProperties()
    {
        return [
            'name' => $this->name,
            'changes' => $this->getDirty()
        ];
    }

    public function assignedSport()
    {
        return $this->hasMany(AssignedSports::class, 'sport_id'); 
    }
}
