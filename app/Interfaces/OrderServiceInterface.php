<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function getOrders(array $filters = []): LengthAwarePaginator;
    
    public function getOrder(string $id): ?Order;
    
    public function updateOrderStatus(string $orderId, string $status, ?string $note = null, ?string $changedBy = null): Order;
    
    public function updateOrder(string $orderId, array $data): Order;
    
    public function cancelOrder(string $orderId, ?string $note = null): Order;
    
    public function getOrderStatistics(array $filters = []): array;
    
    public function deleteOrder(string $orderId): bool;
}