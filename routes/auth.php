<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VendorRegisterController;
use Illuminate\Support\Facades\Route;

// Customer Registration routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Vendor Registration routes
Route::middleware('guest')->group(function () {
    Route::get('/register/vendor', [VendorRegisterController::class, 'showVendorRegistrationForm'])->name('vendor.register');
    Route::post('/register/vendor', [VendorRegisterController::class, 'register']);
});

// Login routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout route
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});