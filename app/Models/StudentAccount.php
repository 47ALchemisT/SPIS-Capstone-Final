<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable; 

class StudentAccount extends Authenticatable 
{
    use HasFactory, Notifiable; 

    protected $table = 'student_accounts';

    protected $fillable = [
        'student_id',
        'role',
        'status',
        'username',
        'password',
    ];


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

}
