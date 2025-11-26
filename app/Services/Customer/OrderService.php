<?php

namespace App\Services\Customer;

use App\Interfaces\Customer\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Contracts\Pagination\Paginator;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function getUserOrders(string $userId, ?string $status = null, int $limit = 10): Paginator
    {
        return $this->orderRepository->getUserOrders($userId, $status, $limit);
    }

    public function getUserOrderStats(string $userId): array
    {
        return $this->orderRepository->getUserOrderStats($userId);
    }

    public function getUserOrderById(string $userId, string $orderId): ?Order
    {
        return $this->orderRepository->getUserOrderById($userId, $orderId);
    }

    public function cancelOrder(string $orderId, string $userId): bool
    {
        return $this->orderRepository->cancelOrder($orderId, $userId);
    }

    public function getRecentOrders(string $userId, int $limit = 5): Paginator
    {
        return $this->orderRepository->getRecentOrders($userId, $limit);
    }

    public function getUserOrderCount(string $userId): int
    {
        return $this->orderRepository->getUserOrderCount($userId);
    }

    public function getUserTotalSpent(string $userId): float
    {
        return $this->orderRepository->getUserTotalSpent($userId);
    }

    // New method for dashboard statistics
    public function getDashboardStats(string $userId): array
    {
        return [
            'total_orders' => $this->getUserOrderCount($userId),
            'total_spent' => $this->getUserTotalSpent($userId),
            'pending_orders' => $this->getUserOrderCountByStatus($userId, 'pending'),
            'recent_orders' => $this->getRecentOrders($userId, 5)
        ];
    }

    public function getUserOrderCountByStatus(string $userId, string $status): int
    {
        return $this->orderRepository->getUserOrderCountByStatus($userId, $status);
    }
}