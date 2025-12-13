<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        $customer = Customer::find($customerId);

        if (!$customer || !$customer->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            abort(403, 'Access denied.');
        }

        // Sync session with database
        if (session('is_admin') !== $customer->is_admin) {
            session(['is_admin' => $customer->is_admin]);
        }

        return $next($request);
    }
}

