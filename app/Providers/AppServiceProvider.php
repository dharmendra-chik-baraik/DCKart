<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Auth & User
use App\Interfaces\AuthServiceInterface;
use App\Services\AuthService;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

// Vendor
use App\Interfaces\VendorRepositoryInterface;
use App\Repositories\VendorRepository;

// Category
use App\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;

// Product
use App\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

// Orders
use App\Interfaces\OrderRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Interfaces\OrderServiceInterface;
use App\Services\OrderService;

// Inventory, Coupons, Reviews, Reports
use App\Interfaces\InventoryRepositoryInterface;
use App\Repositories\InventoryRepository;
use App\Interfaces\CouponRepositoryInterface;
use App\Repositories\CouponRepository;
use App\Interfaces\ReviewRepositoryInterface;
use App\Repositories\ReviewRepository;
use App\Interfaces\ReportRepositoryInterface;
use App\Repositories\ReportRepository;

// Payments & Payout
use App\Interfaces\PaymentRepositoryInterface;
use App\Repositories\PaymentRepository;
use App\Interfaces\PayoutRepositoryInterface;
use App\Repositories\PayoutRepository;

// Tickets
use App\Interfaces\TicketRepositoryInterface;
use App\Repositories\TicketRepository;

// Pages, Settings, Activity Log
use App\Interfaces\PageRepositoryInterface;
use App\Repositories\PageRepository;
use App\Interfaces\SettingRepositoryInterface;
use App\Repositories\SettingRepository;
use App\Interfaces\ActivityLogRepositoryInterface;
use App\Repositories\ActivityLogRepository;


// =========================
// FRONTEND ALIASES
// =========================

use App\Interfaces\Frontend\HomeRepositoryInterface as FrontHomeRepoInterface;
use App\Repositories\Frontend\HomeRepository as FrontHomeRepository;

use App\Interfaces\Frontend\ProductRepositoryInterface as FrontProductRepoInterface;
use App\Repositories\Frontend\ProductRepository as FrontProductRepository;

use App\Interfaces\Frontend\CategoryRepositoryInterface as FrontCategoryRepoInterface;
use App\Repositories\Frontend\CategoryRepository as FrontCategoryRepository;

use App\Interfaces\Frontend\VendorRepositoryInterface as FrontVendorRepoInterface;
use App\Repositories\Frontend\VendorRepository as FrontVendorRepository;

use App\Interfaces\Frontend\CartRepositoryInterface;
use App\Repositories\Frontend\CartRepository;
use App\Interfaces\Frontend\WishlistRepositoryInterface;
use App\Repositories\Frontend\WishlistRepository;
use App\Interfaces\Frontend\PageRepositoryInterface as FrontPageRepoInterface;
use App\Repositories\Frontend\PageRepository as FrontPageRepository;
use App\Interfaces\Frontend\ContactRepositoryInterface;
use App\Repositories\Frontend\ContactRepository;
use App\Interfaces\Frontend\SearchRepositoryInterface;
use App\Repositories\Frontend\SearchRepository;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Auth & User
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Vendor
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);

        // Category & Product
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        // Orders
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        // Inventory, Coupons, Reviews, Reports
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);

        // Payments & Payouts
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(PayoutRepositoryInterface::class, PayoutRepository::class);

        // Tickets
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);

        // Pages, Settings, Activity Logs
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);


        // =========================
        // FRONTEND BINDINGS (ALIAS SAFE)
        // =========================

        $this->app->bind(FrontHomeRepoInterface::class, FrontHomeRepository::class);
        $this->app->bind(FrontProductRepoInterface::class, FrontProductRepository::class);
        $this->app->bind(FrontCategoryRepoInterface::class, FrontCategoryRepository::class);
        $this->app->bind(FrontVendorRepoInterface::class, FrontVendorRepository::class);

        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(WishlistRepositoryInterface::class, WishlistRepository::class);
        $this->app->bind(FrontPageRepoInterface::class, FrontPageRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(SearchRepositoryInterface::class, SearchRepository::class);
    }

    public function boot(): void
    {
        // Auto-load helpers
        $helpers = app_path('Helpers/helpers.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }
}
