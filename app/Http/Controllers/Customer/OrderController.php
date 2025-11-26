<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\OrderService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Display customer orders
     */
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $status = $request->get('status');
        
        $defaultStats = [
            'total' => 0,
            'pending' => 0,
            'confirmed' => 0,
            'processing' => 0,
            'delivered' => 0,
            'cancelled' => 0
        ];
        
        try {
            $orders = $this->orderService->getUserOrders($userId, $status);
            $orderStats = $this->orderService->getUserOrderStats($userId);
            
            // Ensure all stats keys exist
            $orderStats = array_merge($defaultStats, $orderStats);
            
            return view('customer.orders.index', compact('orders', 'orderStats', 'status'));
        } catch (\Exception $e) {
            // Provide default stats on error
            $orderStats = $defaultStats;
            $orders = collect([]);
            
            return view('customer.orders.index', compact('orders', 'orderStats', 'status'))
                ->with('error', 'Failed to load orders: ' . $e->getMessage());
        }
    }

    /**
     * Display order details
     */
    public function show(string $orderId): View|RedirectResponse
    {
        $userId = auth()->id();
        
        try {
            $order = $this->orderService->getUserOrderById($userId, $orderId);
            
            if (!$order) {
                return redirect()->route('customer.orders.index')
                    ->with('error', 'Order not found.');
            }
            
            return view('customer.orders.show', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Failed to load order details: ' . $e->getMessage());
        }
    }

    /**
     * Cancel order
     */
    public function cancel(string $orderId): RedirectResponse
    {
        $userId = auth()->id();
        
        try {
            $result = $this->orderService->cancelOrder($orderId, $userId);
            
            if ($result) {
                return redirect()->back()
                    ->with('success', 'Order has been cancelled successfully.');
            }
            
            return redirect()->back()
                ->with('error', 'Unable to cancel order. Order may already be processed or shipped.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
}