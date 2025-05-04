<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RejectBannedStudents
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->student && $user->student->is_banned) {
            Auth::logout();
            return response()->json([
                'message' => 'تم حظرك من المنصة. يرجى التواصل مع الإدارة.'
            ], 403);
        }

        return $next($request);
    }
}
