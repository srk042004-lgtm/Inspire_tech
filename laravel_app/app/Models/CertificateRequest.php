<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_name',
        'status',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
