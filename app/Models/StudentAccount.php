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
