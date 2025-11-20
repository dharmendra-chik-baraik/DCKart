<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(private Order $order) 
    {
    }

    public function find(string $id): ?Order
    {
        return $this->order
            ->with(['user', 'vendor', 'items.product', 'items.variantValue', 'statusLogs', 'payment'])
            ->find($id);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->order
            ->with(['user', 'vendor', 'items'])
            ->latest();

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    public function getByStatus(string $status, array $filters = []): Collection
    {
        $query = $this->order
            ->with(['user', 'vendor'])
            ->where('order_status', $status)
            ->latest();

        $this->applyFilters($query, $filters);

        return $query->get();
    }

    public function updateStatus(Order $order, string $status, ?string $note = null, ?string $changedBy = null): bool
    {
        return DB::transaction(function () use ($order, $status, $note, $changedBy) {
            $oldStatus = $order->order_status;
            $order->order_status = $status;
            
            if ($order->save()) {
                // Log status change
                OrderStatusLog::create([
                    'id' => \Str::uuid(),
                    'order_id' => $order->id,
                    'status' => $status,
                    'changed_by' => $changedBy,
                    'note' => $note,
                    'created_at' => now(),
                ]);

                return true;
            }

            return false;
        });
    }

    public function updatePaymentStatus(Order $order, string $paymentStatus): bool
    {
        $order->payment_status = $paymentStatus;
        return $order->save();
    }

    public function getVendorOrders(string $vendorId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->order
            ->with(['user', 'items.product'])
            ->where('vendor_id', $vendorId)
            ->latest();

        $this->applyFilters($query, $filters);

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getOrdersWithRelations(array $relations = [], array $filters = []): Collection
    {
        $query = $this->order->with($relations)->latest();

        $this->applyFilters($query, $filters);

        return $query->get();
    }

    public function getSalesStatistics(array $filters = []): array
    {
        $query = $this->order->select(
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(grand_total) as total_revenue'),
            DB::raw('AVG(grand_total) as average_order_value'),
            DB::raw('COUNT(DISTINCT user_id) as unique_customers')
        );

        $this->applyFilters($query, $filters);

        $stats = $query->first();

        // Status distribution
        $statusDistribution = $this->order
            ->select('order_status', DB::raw('COUNT(*) as count'))
            ->when(isset($filters['start_date']), function ($q) use ($filters) {
                $q->whereDate('created_at', '>=', $filters['start_date']);
            })
            ->when(isset($filters['end_date']), function ($q) use ($filters) {
                $q->whereDate('created_at', '<=', $filters['end_date']);
            })
            ->groupBy('order_status')
            ->get()
            ->pluck('count', 'order_status')
            ->toArray();

        return [
            'total_orders' => $stats->total_orders ?? 0,
            'total_revenue' => $stats->total_revenue ?? 0,
            'average_order_value' => $stats->average_order_value ?? 0,
            'unique_customers' => $stats->unique_customers ?? 0,
            'status_distribution' => $statusDistribution,
        ];
    }

    public function delete(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            // Delete related records
            $order->statusLogs()->delete();
            $order->items()->delete();
            $order->payment()->delete();
            
            return $order->delete();
        });
    }

    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('order_number', 'like', "%{$filters['search']}%")
                  ->orWhereHas('user', function ($q) use ($filters) {
                      $q->where('name', 'like', "%{$filters['search']}%")
                        ->orWhere('email', 'like', "%{$filters['search']}%");
                  });
            });
        }

        if (isset($filters['status'])) {
            $query->where('order_status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['vendor_id'])) {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        if (isset($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }
    }
}