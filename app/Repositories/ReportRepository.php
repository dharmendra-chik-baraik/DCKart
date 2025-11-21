<?php

namespace App\Repositories;

use App\Interfaces\ReportRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\VendorProfile;
use App\Models\ProductReview;
use App\Models\Payment;
use App\Models\VendorPayout;
use App\Models\Ticket;
use App\Models\Page;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportRepository implements ReportRepositoryInterface
{
    // Dashboard Statistics
    public function getTotalSales($period = null)
    {
        $query = Order::where('payment_status', 'completed');
        
        if ($period) {
            $query->where('created_at', '>=', $this->getDateRange($period));
        }
        
        return $query->sum('grand_total');
    }

    public function getPendingOrdersCount()
    {
        return Order::where('order_status', 'pending')->count();
    }

    public function getLowStockCount()
    {
        return Product::where('stock', '<', 10)->count();
    }

    public function getTotalUsersCount()
    {
        return User::count();
    }

    public function getTotalProductsCount()
    {
        return Product::count();
    }

    public function getTotalCategoriesCount()
    {
        return Category::count();
    }

    public function getTotalCouponsCount()
    {
        return Coupon::count();
    }

    public function getPendingVendorsCount()
    {
        return VendorProfile::where('status', 'pending')->count();
    }

    public function getPendingReviewsCount()
    {
        return ProductReview::where('status', 'pending')->count();
    }

    public function getPendingPaymentsCount()
    {
        return Payment::where('payment_status', 'pending')->count();
    }

    public function getPendingPayoutsCount()
    {
        return VendorPayout::where('status', 'pending')->count();
    }

    public function getOpenTicketsCount()
    {
        return Ticket::where('status', 'open')->count();
    }

    public function getTotalPagesCount()
    {
        return Page::count();
    }

    public function getTotalShippingMethodsCount()
    {
        return ShippingMethod::count();
    }

    // Sales Reports
    public function getSalesReport($startDate = null, $endDate = null, $type = 'daily')
    {
        $query = Order::where('payment_status', 'completed');
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        return $query->selectRaw('
            DATE(created_at) as date,
            COUNT(*) as total_orders,
            SUM(grand_total) as total_sales,
            AVG(grand_total) as avg_order_value
        ')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    public function getSalesByVendor($startDate = null, $endDate = null)
    {
        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'completed');

        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        return $query->selectRaw('
            vendor_profiles.shop_name,
            SUM(order_items.total) as total_sales,
            COUNT(DISTINCT orders.id) as total_orders,
            AVG(order_items.total) as avg_sale_value
        ')
        ->join('vendor_profiles', 'order_items.vendor_id', '=', 'vendor_profiles.id')
        ->groupBy('vendor_profiles.id', 'vendor_profiles.shop_name')
        ->orderByDesc('total_sales')
        ->get();
    }

    public function getSalesByCategory($startDate = null, $endDate = null)
    {
        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'completed');

        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        return $query->selectRaw('
            categories.name as category_name,
            SUM(order_items.total) as total_sales,
            COUNT(DISTINCT orders.id) as total_orders,
            SUM(order_items.quantity) as total_quantity_sold
        ')
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('total_sales')
        ->get();
    }

    public function getSalesByProduct($startDate = null, $endDate = null, $limit = 10)
    {
        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'completed');

        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        return $query->selectRaw('
            products.name as product_name,
            products.sku as sku,
            SUM(order_items.total) as total_sales,
            SUM(order_items.quantity) as total_quantity_sold,
            COUNT(DISTINCT orders.id) as total_orders
        ')
        ->groupBy('products.id', 'products.name', 'products.sku')
        ->orderByDesc('total_sales')
        ->limit($limit)
        ->get();
    }

    // Analytics
    public function getRevenueTrend($period = '30d')
    {
        $startDate = $this->getDateRange($period);
        
        return Order::where('payment_status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('
                DATE(created_at) as date,
                SUM(grand_total) as revenue,
                COUNT(*) as orders
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getTopProducts($limit = 10, $period = null)
    {
        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'completed');

        if ($period) {
            $query->where('orders.created_at', '>=', $this->getDateRange($period));
        }

        return $query->selectRaw('
            products.name,
            products.sku,
            SUM(order_items.quantity) as total_sold,
            SUM(order_items.total) as total_revenue
        ')
        ->groupBy('products.id', 'products.name', 'products.sku')
        ->orderByDesc('total_sold')
        ->limit($limit)
        ->get();
    }

    public function getTopVendors($limit = 10, $period = null)
    {
        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('vendor_profiles', 'order_items.vendor_id', '=', 'vendor_profiles.id')
            ->where('orders.payment_status', 'completed');

        if ($period) {
            $query->where('orders.created_at', '>=', $this->getDateRange($period));
        }

        return $query->selectRaw('
            vendor_profiles.shop_name,
            COUNT(DISTINCT orders.id) as total_orders,
            SUM(order_items.total) as total_revenue
        ')
        ->groupBy('vendor_profiles.id', 'vendor_profiles.shop_name')
        ->orderByDesc('total_revenue')
        ->limit($limit)
        ->get();
    }

    public function getTopCategories($limit = 10, $period = null)
    {
        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'completed');

        if ($period) {
            $query->where('orders.created_at', '>=', $this->getDateRange($period));
        }

        return $query->selectRaw('
            categories.name,
            COUNT(DISTINCT orders.id) as total_orders,
            SUM(order_items.total) as total_revenue,
            SUM(order_items.quantity) as total_quantity
        ')
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('total_revenue')
        ->limit($limit)
        ->get();
    }

    // Performance Metrics
    public function getConversionRate($period = null)
    {
        $query = Order::query();
        
        if ($period) {
            $query->where('created_at', '>=', $this->getDateRange($period));
        }

        $totalOrders = $query->count();
        $completedOrders = $query->where('payment_status', 'completed')->count();

        return $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;
    }

    public function getAverageOrderValue($period = null)
    {
        $query = Order::where('payment_status', 'completed');
        
        if ($period) {
            $query->where('created_at', '>=', $this->getDateRange($period));
        }

        return $query->avg('grand_total') ?? 0;
    }

    public function getCustomerRetentionRate($period = null)
    {
        // This is a simplified version - you might want to implement a more sophisticated retention calculation
        $startDate = $period ? $this->getDateRange($period) : now()->subDays(30);
        
        $returningCustomers = Order::where('created_at', '>=', $startDate)
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        $totalCustomers = Order::where('created_at', '>=', $startDate)
            ->distinct('user_id')
            ->count('user_id');

        return $totalCustomers > 0 ? ($returningCustomers / $totalCustomers) * 100 : 0;
    }

    // Helper method to get date ranges
    private function getDateRange($period)
    {
        return match($period) {
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            default => now()->subDays(30),
        };
    }
}