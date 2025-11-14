<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // User must be logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If user has no role
        if (!$user->role) {
            abort(403, 'Your account has no role assigned.');
        }

        // Check if user role matches allowed roles
        if (!in_array($user->role->name, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}

