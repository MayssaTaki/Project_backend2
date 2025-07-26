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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id', 'user_id');
    }
}
