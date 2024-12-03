<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'students';

    protected $fillable = [
        'first_name',
        'last_name',
        'id_number',
        'univ_email',
        'college',
        'contact',
        'status'
    ];

    // Custom activity description for better logging
    protected function getActivityDescription($type)
    {
        $fullName = "{$this->first_name} {$this->last_name}";
        
        switch ($type) {
            case 'create':
                return "added new student: {$fullName}";
            case 'update':
                return "updated student information for: {$fullName}";
            case 'delete':
                return "removed student: {$fullName}";
            default:
                return parent::getActivityDescription($type);
        }
    }

    // Only log important changes
    protected function getActivityProperties()
    {
        return [
            'student_id' => $this->id_number,
            'name' => "{$this->first_name} {$this->last_name}",
            'college' => $this->college,
            'status' => $this->status,
            'changes' => $this->getDirty()
        ];
    }

    public function studentAccount()
    {
        return $this->hasOne(StudentAccount::class, 'student_id'); 
    }

    public function studentID()
    {
        return $this->hasOne(StudentPlayer::class, 'student_player_id'); 
    }
}
