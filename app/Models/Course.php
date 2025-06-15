<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
      protected $fillable = [
        'teacher_id',
        'category_id',
        'name',
        'price',
        'description',
        'accepted' // status: null=pending, 1=accepted, 0=rejected
    ];
}
