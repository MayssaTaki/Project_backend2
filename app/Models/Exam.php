<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
      protected $fillable = [
        'course_id',
        'duration_minutes'
    ];
  
   public function questions() {
    return $this->hasMany(Question::class);
}
public function course()
{
    return $this->belongsTo(Course::class, 'course_id');
}
  }