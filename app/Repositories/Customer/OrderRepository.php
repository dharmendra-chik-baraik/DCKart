<?php

namespace App\Repositories\Customer;

use App\Interfaces\Customer\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Str;

class OrderRepository implements OrderRepositoryInterface
{
    public function getUserOrders(string $userId, ?string $status = null, int $limit = 10): Paginator
    {
        $query = Order::where('user_id', $userId)
            ->with([
                'items.product.images',
                'vendor',
                'statusLogs',
            ])
            ->latest();

        if ($status) {
            $query->where('order_status', $status);
        }

        return $query->paginate($limit);
    }

    public function getUserOrderStats(string $userId): array
    {
        try {
            return [
                'total' => Order::where('user_id', $userId)->count(),
                'pending' => Order::where('user_id', $userId)->where('order_status', 'pending')->count(),
                'confirmed' => Order::where('user_id', $userId)->where('order_status', 'confirmed')->count(),
                'processing' => Order::where('user_id', $userId)->where('order_status', 'processing')->count(),
                'delivered' => Order::where('user_id', $userId)->where('order_status', 'delivered')->count(),
                'cancelled' => Order::where('user_id', $userId)->where('order_status', 'cancelled')->count(),
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'pending' => 0,
                'confirmed' => 0,
                'processing' => 0,
                'delivered' => 0,
                'cancelled' => 0,
            ];
        }
    }

    public function getUserOrderById(string $userId, string $orderId): ?Order
    {
        return Order::where('user_id', $userId)
            ->where('id', $orderId)
            ->with([
                'items.product.images',
                'vendor',
                'statusLogs',
                'payment',
                'user.addresses'
            ])
            ->first();
    }

    public function getUserOrderCount(string $userId): int
    {
        return Order::where('user_id', $userId)->count();
    }

    public function getUserOrderCountByStatus(string $userId, string $status): int
    {
        return Order::where('user_id', $userId)
            ->where('order_status', $status)
            ->count();
    }

    public function getUserTotalSpent(string $userId): float
    {
        return (float) Order::where('user_id', $userId)
            ->where('payment_status', 'completed')
            ->sum('grand_total');
    }

    public function updateOrderStatus(string $orderId, string $status): bool
    {
        $order = Order::find($orderId);

        if (!$order) {
            return false;
        }

        // Only allow cancellation for pending and confirmed orders
        if ($status === 'cancelled' && !in_array($order->order_status, ['pending', 'confirmed'])) {
            return false;
        }

        // Update order status
        $order->order_status = $status;
        $order->save();

        // Add status log
        $order->statusLogs()->create([
            'id' => (string) Str::uuid(),
            'status' => $status,
            'changed_by' => 'customer',
            'note' => $status === 'cancelled' ? 'Order cancelled by customer' : 'Status updated by system',
        ]);

        return true;
    }

    public function getRecentOrders(string $userId, int $limit = 5): Paginator
    {
        return Order::where('user_id', $userId)
            ->with(['items.product.images', 'vendor'])
            ->latest()
            ->paginate($limit);
    }

    public function cancelOrder(string $orderId, string $userId): bool
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order || !$order->canBeCancelled()) {
            return false;
        }

        return $this->updateOrderStatus($orderId, 'cancelled');
    }

    // New method for dashboard
    public function getMonthlyOrderStats(string $userId): array
    {
        return Order::where('user_id', $userId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('COUNT(*) as total_orders, SUM(grand_total) as total_amount')
            ->first()
            ->toArray();
    }
}