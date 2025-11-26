<?php

namespace App\Interfaces\Customer;

use App\Models\Order;
use Illuminate\Contracts\Pagination\Paginator;

interface OrderRepositoryInterface
{
    public function getUserOrders(string $userId, ?string $status = null, int $limit = 10): Paginator;
    
    public function getUserOrderStats(string $userId): array;
    
    public function getUserOrderById(string $userId, string $orderId): ?Order;
    
    public function getUserOrderCount(string $userId): int;
    
    public function getUserOrderCountByStatus(string $userId, string $status): int;
    
    public function getUserTotalSpent(string $userId): float;
    
    public function updateOrderStatus(string $orderId, string $status): bool;
    
    public function getRecentOrders(string $userId, int $limit = 5): Paginator;
    
    public function cancelOrder(string $orderId, string $userId): bool;
}