<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function find(string $id): ?Order;
    
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    
    public function getByStatus(string $status, array $filters = []): Collection;
    
    public function updateStatus(Order $order, string $status, ?string $note = null, ?string $changedBy = null): bool;
    
    public function updatePaymentStatus(Order $order, string $paymentStatus): bool;
    
    public function getVendorOrders(string $vendorId, array $filters = []): LengthAwarePaginator;
    
    public function getOrdersWithRelations(array $relations = [], array $filters = []): Collection;
    
    public function getSalesStatistics(array $filters = []): array;
    
    public function delete(Order $order): bool;
}