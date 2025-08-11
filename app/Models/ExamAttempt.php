<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ExamAttempt extends Model
{
    protected $fillable = ['exam_id', 'student_id', 'started_at', 'ended_at', 'score', 'status'];
protected $casts = [
    'started_at' => 'datetime',
    'ended_at' => 'datetime',
];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}