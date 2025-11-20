<?php

namespace App\Services;

use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\OrderServiceInterface;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {
    }

    public function getOrders(array $filters = []): LengthAwarePaginator
    {
        return $this->orderRepository->paginate($filters);
    }

    public function getOrder(string $id): ?Order
    {
        return $this->orderRepository->find($id);
    }

    public function updateOrderStatus(string $orderId, string $status, ?string $note = null, ?string $changedBy = null): Order
    {
        $order = $this->orderRepository->find($orderId);
        
        if (!$order) {
            throw new \Exception("Order not found");
        }

        if ($status === 'cancelled' && !$order->canBeCancelled()) {
            throw new \Exception("Order cannot be cancelled in its current status");
        }

        $success = $this->orderRepository->updateStatus($order, $status, $note, $changedBy);
        
        if (!$success) {
            throw new \Exception("Failed to update order status");
        }

        return $order->fresh();
    }

    public function updateOrder(string $orderId, array $data): Order
    {
        $order = $this->orderRepository->find($orderId);
        
        if (!$order) {
            throw new \Exception("Order not found");
        }

        DB::transaction(function () use ($order, $data) {
            if (isset($data['order_status']) && $data['order_status'] !== $order->order_status) {
                $this->orderRepository->updateStatus(
                    $order, 
                    $data['order_status'], 
                    $data['note'] ?? null,
                    auth()->id()
                );
                unset($data['order_status']);
            }

            if (isset($data['payment_status'])) {
                $this->orderRepository->updatePaymentStatus($order, $data['payment_status']);
                unset($data['payment_status']);
            }

            // Update other fields
            $order->update($data);
        });

        return $order->fresh();
    }

    public function cancelOrder(string $orderId, ?string $note = null): Order
    {
        return $this->updateOrderStatus($orderId, 'cancelled', $note, auth()->id());
    }

    public function getOrderStatistics(array $filters = []): array
    {
        return $this->orderRepository->getSalesStatistics($filters);
    }

    public function deleteOrder(string $orderId): bool
    {
        $order = $this->orderRepository->find($orderId);
        
        if (!$order) {
            throw new \Exception("Order not found");
        }

        if (!in_array($order->order_status, ['cancelled', 'pending'])) {
            throw new \Exception("Cannot delete order with status: {$order->order_status}");
        }

        return $this->orderRepository->delete($order);
    }
}