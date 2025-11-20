<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\OrderManagementController;

use Illuminate\Support\Facades\Route;


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Users
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{id}', [UserController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/status', [UserController::class, 'changeStatus'])->name('change-status');
});

// Vendors
Route::prefix('vendors')->name('vendors.')->group(function () {
    Route::get('/', [VendorController::class, 'index'])->name('index');
    Route::get('/create', [VendorController::class, 'create'])->name('create');
    Route::post('/', [VendorController::class, 'store'])->name('store');
    Route::get('/{id}', [VendorController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [VendorController::class, 'edit'])->name('edit');
    Route::put('/{id}', [VendorController::class, 'update'])->name('update');
    Route::delete('/{id}', [VendorController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/status', [VendorController::class, 'changeStatus'])->name('change-status');
    Route::post('/{id}/toggle-verification', [VendorController::class, 'toggleVerification'])->name('toggle-verification');
});

// Categories Management
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/status', [CategoryController::class, 'changeStatus'])->name('change-status');
    Route::get('/tree/view', [CategoryController::class, 'tree'])->name('tree');
});

// Products Management
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/status', [ProductController::class, 'changeStatus'])->name('change-status');
    Route::post('/{id}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('toggle-featured');
    Route::post('/{id}/update-stock', [ProductController::class, 'updateStock'])->name('update-stock');
});


// Orders Management
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/statistics', [OrderManagementController::class, 'statistics'])->name('orders.statistics');
    Route::get('/{id}', [OrderManagementController::class, 'show'])->name('orders.show');
    Route::put('/{id}', [OrderManagementController::class, 'update'])->name('orders.update');
    Route::put('/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.update-status');
    Route::put('/{id}/cancel', [OrderManagementController::class, 'cancel'])->name('orders.cancel');
    Route::delete('/{id}', [OrderManagementController::class, 'destroy'])->name('orders.destroy');
});
