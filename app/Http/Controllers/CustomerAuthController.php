<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegister()
    {
        return view('auth.register'); 
    }

    /**
     * Handle registration form submission
     */
    public function registerSubmit(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'password' => 'required|min:6|confirmed', // use confirmed for password confirmation
        ]);

        // Create customer
        Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false, // default new users to non-admin
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }

    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('auth.login'); 
    }

    /**
     * Handle login form submission
     */
    public function loginSubmit(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Find customer
        $customer = Customer::where('email', $credentials['email'])->first();

        // Check credentials
        if ($customer && Hash::check($credentials['password'], $customer->password)) {

            // Store session
            session([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'is_admin' => $customer->is_admin ?? false,
            ]);

            // Redirect based on role
            if ($customer->is_admin ?? false) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome Admin, ' . $customer->name . '!');
            } else {
                return redirect()->route('home')
                    ->with('success', 'Welcome back, ' . $customer->name . '!');
            }
        }

        // Invalid credentials
        return back()->with('error', 'Invalid email or password.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $request->session()->flush(); // Clear all session data
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
