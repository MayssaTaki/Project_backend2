<?php

namespace App\Services;

use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Exceptions\AuthenticationException;
use Illuminate\Support\Facades\Auth;



class AuthService
{
    public function login(array $credentials): array
    {
        $email = $credentials['email']; 
    
        $key = $this->throttleKey($email);
    
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw new AuthenticationException("محاولات تسجيل دخول كثيرة جدًا، يرجى المحاولة بعد {$seconds} ثانية.", 429);
        }
    
        $user = \App\Models\User::where('email', $email)->first();
    
        if ($user && $user->student && $user->student->is_banned) {
            throw new \Exception('تم حظرك من المنصة. يرجى التواصل مع الإدارة.', 403);
        }
    
        if (!$token = JWTAuth::attempt($credentials)) {
            RateLimiter::hit($key, 60); 
            throw new AuthenticationException();
        }
    
        RateLimiter::clear($key); 
    
        $user = auth()->user();
    
        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
            'refresh_token' => JWTAuth::fromUser($user),
        ];
    }
    

    private function throttleKey(string $email): string
    {
        $ip = request()?->ip() ?? '127.0.0.1'; 
        return Str::lower($email) . '|' . $ip;    }

        public function refreshToken()
    {
        return ['token' => JWTAuth::refresh()];
    }

    public function logoutUser()
    {
        Auth::logout();
    }
}
