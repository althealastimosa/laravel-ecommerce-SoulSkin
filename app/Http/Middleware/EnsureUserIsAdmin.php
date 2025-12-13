<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated via session (for Customer model)
        $isAdmin = session('is_admin', false);
        
        // Also check Laravel's Auth facade (for User model if used)
        $user = Auth::user();
        
        // Determine admin status
        $hasAdminAccess = false;
        
        if ($user && ($user->is_admin ?? false)) {
            $hasAdminAccess = true;
        } elseif ($isAdmin) {
            $hasAdminAccess = true;
        }
        
        // If not authenticated at all
        if (!$user && !session('customer_id')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }
        
        // If not admin
        if (!$hasAdminAccess) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
