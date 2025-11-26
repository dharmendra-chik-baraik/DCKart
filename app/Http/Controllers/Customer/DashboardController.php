<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\OrderService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Display customer dashboard
     */
    public function index(): View
    {
        $userId = auth()->id();
        
        try {
            $orderStats = $this->orderService->getUserOrderStats($userId);
            $recentOrders = $this->orderService->getRecentOrders($userId, 5);
            $totalOrders = $this->orderService->getUserOrderCount($userId);
            $totalSpent = $this->orderService->getUserTotalSpent($userId);
            
            // Ensure all stats keys exist
            $defaultStats = [
                'total' => 0,
                'pending' => 0,
                'confirmed' => 0,
                'processing' => 0,
                'delivered' => 0,
                'cancelled' => 0
            ];
            
            $orderStats = array_merge($defaultStats, $orderStats);
            
            return view('customer.dashboard', compact(
                'orderStats', 
                'recentOrders', 
                'totalOrders', 
                'totalSpent'
            ));
        } catch (\Exception $e) {
            // Provide default values on error
            $orderStats = $defaultStats ?? [
                'total' => 0,
                'pending' => 0,
                'confirmed' => 0,
                'processing' => 0,
                'delivered' => 0,
                'cancelled' => 0
            ];
            $recentOrders = collect([]);
            $totalOrders = 0;
            $totalSpent = 0;
            
            return view('customer.dashboard', compact(
                'orderStats', 
                'recentOrders', 
                'totalOrders', 
                'totalSpent'
            ))->with('error', 'Failed to load dashboard data: ' . $e->getMessage());
        }
    }
}