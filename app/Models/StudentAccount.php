<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StudentAccount extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    protected $table = 'student_accounts';

    protected $with = ['student'];

    protected $fillable = [
        'student_id',
        'role',
        'status',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Custom activity description for better logging
    protected function getActivityDescription($type)
    {
        $student = $this->student;
        $studentName = $student ? "{$student->first_name} {$student->last_name}" : $this->username;
        
        switch ($type) {
            case 'create':
                return "created new account for: {$studentName}";
            case 'update':
                if ($this->isDirty('password')) {
                    return "updated password for: {$studentName}";
                }
                return "updated account for: {$studentName}";
            case 'delete':
                return "deleted account for: {$studentName}";
            default:
                return parent::getActivityDescription($type);
        }
    }

    // Only log important changes
    protected function getActivityProperties()
    {
        return [
            'username' => $this->username,
            'role' => $this->role,
            'student_id' => $this->student_id,
            // Never log password changes
            'changes' => array_diff_key($this->getDirty(), ['password' => '', 'remember_token' => ''])
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function studentID()
    {
        return $this->HasMany(StudentPlayer::class, 'student_player_id');
    }
    public function facilitatorID(){
        return $this->hasMany(AssignedSports::class, 'facilitator_id');
    }

    public function facilitator(){
        return $this->hasMany(FacilitatorSubmits::class, 'facilitator_id');
    }

    public function ffo_facilitator(){
        return $this->hasMany(FfoFacilitatorSubmits::class, 'facilitator_id');
    }

    public function facilitatorSubmition(){
        return $this->hasMany(FacilitatorRankSubmitions::class, 'facilitator_id');
    }

    public function com_head(){
        return $this->hasMany(ComHeadColleges::class, 'com_head_id');
    }

}
