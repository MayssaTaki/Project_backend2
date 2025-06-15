<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureTeacherRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Not authenticated');
        }

        if (!method_exists($user, 'isTeacher')) {
            abort(500, 'Authentication system error');
        }

        if ($user->isTeacher()) {
            return $next($request);
        }

        abort(403, 'Teacher access required. Your role: '.$user->role);
    }
}
