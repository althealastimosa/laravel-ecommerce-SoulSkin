<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    /**
     * Validate that the current session belongs to an admin customer.
     */
    public function handle(Request $request, Closure $next)
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            return redirect()->route('login');
        }

        $customer = Customer::find($customerId);

        if (!$customer || !$customer->is_admin) {
            abort(403, 'Access denied.');
        }

        if (session('is_admin') !== $customer->is_admin) {
            session(['is_admin' => $customer->is_admin]);
        }

        return $next($request);
    }
}
