<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    
    public function showRegister()
    {
        return view('auth.register');
    }

  
    public function registerSubmit(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'password' => 'required|min:6',
        ]);

       
        Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false, 
        ]);

       
        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }

    
    public function showLogin()
    {
        return view('auth.login');
    }

    
    public function loginSubmit(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

       
        $customer = Customer::where('email', $credentials['email'])->first();

        
        if ($customer && Hash::check($credentials['password'], $customer->password)) {
            
            $isAdmin = $customer->is_admin ?? false;
            
            session([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'is_admin' => $isAdmin,
            ]);

            
            if ($isAdmin) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $customer->name . '!');
            }

            return redirect()->route('home')->with('success', 'Welcome back, ' . $customer->name . '!');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $request->session()->flush(); 
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
