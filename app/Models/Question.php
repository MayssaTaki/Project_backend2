<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class question extends Model
{

protected $fillable = [
    'question_text','exam_id'];

  public function choices() {
    return $this->hasMany(Choice::class);
}



}
