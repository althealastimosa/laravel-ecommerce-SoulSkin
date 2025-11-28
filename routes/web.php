<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerAuthController;

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Registration routes
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'registerSubmit'])->name('register.submit');

// Login routes
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'loginSubmit'])->name('login.submit');

// Home page (after login)
Route::get('/home', function () {
    return view('home');
})->name('home');

// Admin dashboard (protected by middleware)
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});
