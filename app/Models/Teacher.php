<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Teacher extends Model
{
    use HasFactory;     use Notifiable;


    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'specialization',
        'Previous_experiences',
        'phone',
        'profile_image',
        'country',
        'city',
        'gender',
        'status',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }

    public function getProfileImageAttribute($value)
    {
        if (!empty($value)) {
            return asset('storage/' . $value);
        }

        return asset('images/default-user-image.webp');
    }

    public function evaluations()
    {
        return $this->hasMany(TeachersEvaluation::class, 'teacher_id', 'user_id');
    }

    public function averageRating()
    {
        return $this->evaluations()->avg('evaluation_value');
    }
    public function courses()
{
    return $this->hasMany(Course::class);
}
}
