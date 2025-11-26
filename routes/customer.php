<?php

use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\Customer\ReviewController;

use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Orders
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

// Wishlist
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
Route::delete('/wishlist', [WishlistController::class, 'clear'])->name('wishlist.clear');
Route::post('/{product}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');

// Notifications
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');

// Profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

// Addresses
Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
Route::patch('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
Route::patch('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
