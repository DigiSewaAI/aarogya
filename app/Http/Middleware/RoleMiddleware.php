<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If no specific role required, allow all authenticated users
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has any of the allowed roles
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // If user is a patient, redirect to patient dashboard
        if ($user->role === 'patient') {
            return redirect()->route('patient.dashboard');
        }

        // For other unauthorized access, show 403 or redirect home
        abort(403, 'Unauthorized access.');
    }
}