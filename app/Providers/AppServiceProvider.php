<?php

namespace App\Providers;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CouponRepositoryInterface;
use App\Interfaces\InventoryRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
// repositories
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\PaymentRepositoryInterface;
use App\Interfaces\PayoutRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
// service
use App\Interfaces\ReportRepositoryInterface;
use App\Interfaces\ReviewRepositoryInterface;
// Order Management
use App\Interfaces\TicketRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VendorRepositoryInterface;
use App\Repositories\CategoryRepository;
// inventory
use App\Repositories\CouponRepository;
use App\Repositories\InventoryRepository;
// coupon admin management
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
// admin reviews
use App\Repositories\PayoutRepository;
use App\Repositories\ProductRepository;
// admin sales report
use App\Repositories\ReportRepository;
use App\Repositories\ReviewRepository;
// admin payout and payments
use App\Repositories\TicketRepository;
use App\Repositories\UserRepository;
use App\Repositories\VendorRepository;
use App\Services\AuthService;
// ticket management
use App\Services\OrderService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);

        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);

        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);

        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);

        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(PayoutRepositoryInterface::class, PayoutRepository::class);
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load helper file
        $helpers = app_path('Helpers/helpers.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }
}
