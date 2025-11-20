<?php
// App/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Basic stats
        $stats = [
            'total_users' => User::count(),
            'customers_count' => User::where('role', 'customer')->count(),
            'total_vendors' => VendorProfile::count(),
            'approved_vendors' => VendorProfile::where('status', 'approved')->count(),
            'pending_vendors' => VendorProfile::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'low_stock_products' => Product::where('stock', '<', 10)->where('stock_status', 'in_stock')->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'total_revenue' => Order::where('order_status', 'completed')->sum('grand_total'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::where('order_status', 'completed')->whereDate('created_at', today())->sum('grand_total'),
            'total_categories' => Category::count(),
            'active_categories' => Category::where('status', true)->count(),
        ];

        // Recent data
        $recentOrders = Order::with(['user', 'vendor'])->latest()->take(5)->get();
        $recentProducts = Product::with(['vendor.user', 'images'])->latest()->take(5)->get();
        $recentVendors = VendorProfile::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentProducts', 'recentVendors'));
    }

    // ... rest of your methods
}