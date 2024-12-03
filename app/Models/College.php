<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'shortName', 'teamName', 'description'];

    // Custom activity description for better logging
    protected function getActivityDescription($type)
    {
        switch ($type) {
            case 'create':
                return "added new college: {$this->name} ({$this->shortName})";
            case 'update':
                return "updated college: {$this->name} ({$this->shortName})";
            case 'delete':
                return "removed college: {$this->name} ({$this->shortName})";
            default:
                return parent::getActivityDescription($type);
        }
    }

    // Only log important changes
    protected function getActivityProperties()
    {
        return [
            'name' => $this->name,
            'short_name' => $this->shortName,
            'team_name' => $this->teamName,
            'changes' => $this->getDirty()
        ];
    }

    // A college can have many assigned teams
    public function assignedTeams()
    {
        return $this->hasMany(AssignedTeams::class, 'college_id'); 
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'college', 'shortName');
    }
}
