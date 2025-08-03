<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    protected $fillable = [
        'course_id',
        'video_url',
        'uploaded_at',
        'file_size',
        'video_name'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}