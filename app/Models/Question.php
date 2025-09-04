<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

protected $fillable = [
    'question_text','exam_id'];

  public function choices() {
    return $this->hasMany(Choice::class);
}

   public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'question_id', 'id');
    }

}
