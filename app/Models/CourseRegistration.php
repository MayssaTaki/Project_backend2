<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    protected $fillable = [
        'course_id',
        'student_id',
        'status',
        'registered_at'
    ];

    protected $casts = [
        'registered_at' => 'datetime'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}