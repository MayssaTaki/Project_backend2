<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;


class PasswordResetRepository 
{
    
    public function storeResetToken(string $email, string $token)
    {
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );
    }
    
    public function getByEmail(string $email)
    {
        return DB::table('password_resets')->where('email', $email)->first();
    }
    
    public function delete(string $email)
    {
        DB::table('password_resets')->where('email', $email)->delete();
    }
    
  
}
