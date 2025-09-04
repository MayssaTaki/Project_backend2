<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachersEvaluation extends Model
{
    protected $table = 'teachers_evaluations';
    
    protected $fillable = [
        'teacher_id',
        'student_id',
        'evaluation_value'
    ];

    protected $casts = [
        'evaluation_value' => 'integer'
    ];

    // Validation rules
    public static function rules()
    {
        return [    
            'evaluation_value' => 'required|integer|min:0|max:5'
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'student_id', 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }
}