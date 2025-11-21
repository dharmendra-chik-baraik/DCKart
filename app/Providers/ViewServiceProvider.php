<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Interfaces\ReportRepositoryInterface;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(ReportRepositoryInterface $reportRepository): void
    {
        // Share data with all admin views
        View::composer('admin.*', function ($view) use ($reportRepository) {
            $view->with([
                'pendingVendorsCount' => $reportRepository->getPendingVendorsCount(),
                'pendingOrdersCount' => $reportRepository->getPendingOrdersCount(),
                'lowStockCount' => $reportRepository->getLowStockCount(),
                'totalUsers' => $reportRepository->getTotalUsersCount(),
                'totalProducts' => $reportRepository->getTotalProductsCount(),
                'totalCategories' => $reportRepository->getTotalCategoriesCount(),
                'totalCoupons' => $reportRepository->getTotalCouponsCount(),
                'pendingReviewsCount' => $reportRepository->getPendingReviewsCount(),
                'pendingPaymentsCount' => $reportRepository->getPendingPaymentsCount(),
                'pendingPayoutsCount' => $reportRepository->getPendingPayoutsCount(),
                'openTicketsCount' => $reportRepository->getOpenTicketsCount(),
                'totalPages' => $reportRepository->getTotalPagesCount(),
                'totalShippingMethods' => $reportRepository->getTotalShippingMethodsCount(),
                'totalSales' => $reportRepository->getTotalSales('30d'),
                'totalOrders' => $reportRepository->getPendingOrdersCount(),
                'bulkUpdateEnabled' => true,
            ]);
        });
    }
}