<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'registerSubmit'])->name('register.submit');

    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'loginSubmit'])->name('login.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Home page
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Products CRUD
    Route::resource('products', ProductController::class);
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});
