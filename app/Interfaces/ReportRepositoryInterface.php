<?php

namespace App\Interfaces;

interface ReportRepositoryInterface
{
    // Dashboard statistics
    public function getTotalSales($period = null);
    public function getPendingOrdersCount();
    public function getLowStockCount();
    public function getTotalUsersCount();
    public function getTotalProductsCount();
    public function getTotalCategoriesCount();
    public function getTotalCouponsCount();
    public function getPendingVendorsCount();
    public function getPendingReviewsCount();
    public function getPendingPaymentsCount();
    public function getPendingPayoutsCount();
    public function getOpenTicketsCount();
    public function getTotalPagesCount();
    public function getTotalShippingMethodsCount();

    // Sales Reports
    public function getSalesReport($startDate = null, $endDate = null, $type = 'daily');
    public function getSalesByVendor($startDate = null, $endDate = null);
    public function getSalesByCategory($startDate = null, $endDate = null);
    public function getSalesByProduct($startDate = null, $endDate = null, $limit = 10);
    
    // Analytics
    public function getRevenueTrend($period = '30d');
    public function getTopProducts($limit = 10, $period = null);
    public function getTopVendors($limit = 10, $period = null);
    public function getTopCategories($limit = 10, $period = null);
    
    // Performance Metrics
    public function getConversionRate($period = null);
    public function getAverageOrderValue($period = null);
    public function getCustomerRetentionRate($period = null);
}