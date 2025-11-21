<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\TicketController;    



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

// Inventory Management Routes
Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('/out-of-stock', [InventoryController::class, 'outOfStock'])->name('inventory.out-of-stock');
    Route::put('/{product}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::post('/bulk-update', [InventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
    Route::post('/update-stock-status', [InventoryController::class, 'updateStockStatus'])->name('inventory.update-stock-status');
});


// Coupon Management Routes
Route::prefix('coupons')->group(function () {
    Route::get('/', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('/', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
    Route::post('/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::get('/{coupon}/usage', [CouponController::class, 'usage'])->name('coupons.usage');
});

// Reviews Management Routes
Route::prefix('reviews')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/pending', [ReviewController::class, 'pending'])->name('reviews.pending');
    Route::get('/approved', [ReviewController::class, 'approved'])->name('reviews.approved');
    Route::get('/rejected', [ReviewController::class, 'rejected'])->name('reviews.rejected');
    Route::post('/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::post('/{review}/pending', [ReviewController::class, 'markPending'])->name('reviews.mark-pending');
    Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/bulk-actions', [ReviewController::class, 'bulkActions'])->name('reviews.bulk-actions');
});

// Reports Routes
Route::prefix('reports')->group(function () {
    Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/products', [ReportController::class, 'products'])->name('reports.products');
    Route::get('/vendors', [ReportController::class, 'vendors'])->name('reports.vendors');
    Route::get('/export-sales', [ReportController::class, 'exportSales'])->name('reports.export-sales');
});

// Payments Management Routes
Route::prefix('payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/{id}/update-status', [PaymentController::class, 'updateStatus'])->name('payments.update-status');
    Route::post('/{id}/refund', [PaymentController::class, 'processRefund'])->name('payments.refund');
    Route::post('/bulk-update', [PaymentController::class, 'bulkUpdate'])->name('payments.bulk-update');
});

// Payouts Management Routes
Route::prefix('payouts')->group(function () {
    Route::get('/', [PayoutController::class, 'index'])->name('payouts.index');
    Route::get('/create', [PayoutController::class, 'create'])->name('payouts.create');
    Route::post('/', [PayoutController::class, 'store'])->name('payouts.store');
    Route::get('/{id}', [PayoutController::class, 'show'])->name('payouts.show');
    Route::post('/{id}/update-status', [PayoutController::class, 'updateStatus'])->name('payouts.update-status');
    Route::post('/bulk-process', [PayoutController::class, 'bulkProcess'])->name('payouts.bulk-process');
    Route::get('/vendor/{vendorId}/earnings', [PayoutController::class, 'vendorEarnings'])->name('payouts.vendor-earnings');
    Route::post('/vendor/{vendorId}/generate', [PayoutController::class, 'generatePayout'])->name('payouts.vendor.generate-payout');
});

// Support Tickets Routes
Route::prefix('tickets')->group(function () {
    Route::get('/', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::put('/{id}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('/{id}/message', [TicketController::class, 'addMessage'])->name('tickets.add-message');
    Route::post('/{id}/status', [TicketController::class, 'changeStatus'])->name('tickets.change-status');
    Route::post('/{id}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/bulk-update', [TicketController::class, 'bulkUpdate'])->name('tickets.bulk-update');
    Route::get('/attachment/{filename}', [TicketController::class, 'downloadAttachment'])->name('tickets.download-attachment');
});