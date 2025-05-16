<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use HasFactory;     use Notifiable;


    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
       'date_of_birth',
        'phone',
        'profile_image',
        'country',
        'city',
        'gender',
         'is_banned'
    ];

    protected $casts = [
        'is_banned' => 'boolean',
    ];
    
    public function isBanned()
    {
        return $this->is_banned;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }
    
    protected static function booted()
    {
        static::created(function ($model) {
            $model->wallet()->create(['balance' => 0]);
        });
    }

    public function getImageAttribute()
{
    if ($this->profile_image) {
        return asset('storage/' . $this->profile_image);
    }
    return asset('images/default-user-image.webp');
}

        

   
}