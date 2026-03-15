<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'district',
        'nic',
        'qualification',
        'last_degree',
        'enrolled_course',
        'enrollment_status',
        'assigned_teacher_id',
        'total_fee',
        'paid_fee',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'assigned_teacher_id');
    }

    public function certificateRequests()
    {
        return $this->hasMany(CertificateRequest::class);
    }
}
