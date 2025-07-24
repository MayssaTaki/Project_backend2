<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
      protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'price',
        'description',
        'accepted'
    ];
}
