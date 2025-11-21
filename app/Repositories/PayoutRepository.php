<?php

namespace App\Repositories;

use App\Interfaces\PayoutRepositoryInterface;
use App\Models\VendorPayout;
use App\Models\VendorProfile;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class PayoutRepository implements PayoutRepositoryInterface
{
    public function getAllPayouts($filters = [])
    {
        $query = VendorPayout::with(['vendor', 'vendor.user'])
            ->orderBy('created_at', 'desc');

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['vendor_id']) && $filters['vendor_id'] !== '') {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('remarks', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function($q) use ($search) {
                      $q->where('shop_name', 'like', "%{$search}%");
                  });
            });
        }

        if (isset($filters['date_from']) && $filters['date_from'] !== '') {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to'] !== '') {
            $query->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }

        return $query->paginate(20);
    }

    public function getPayoutById($id)
    {
        return VendorPayout::with(['vendor', 'vendor.user'])->findOrFail($id);
    }

    public function getPayoutsByStatus($status)
    {
        return VendorPayout::with(['vendor', 'vendor.user'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function createPayout($data)
    {
        return VendorPayout::create($data);
    }

    public function updatePayoutStatus($id, $status, $transactionId = null, $remarks = null)
    {
        $payout = VendorPayout::findOrFail($id);
        
        $updateData = ['status' => $status];
        if ($transactionId) {
            $updateData['transaction_id'] = $transactionId;
        }
        if ($remarks) {
            $updateData['remarks'] = $remarks;
        }

        $payout->update($updateData);

        return $payout;
    }

    public function getPayoutStats()
    {
        return [
            'total' => VendorPayout::count(),
            'pending' => VendorPayout::where('status', 'pending')->count(),
            'processed' => VendorPayout::where('status', 'processed')->count(),
            'failed' => VendorPayout::where('status', 'failed')->count(),
            'total_amount' => VendorPayout::sum('amount'),
            'pending_amount' => VendorPayout::where('status', 'pending')->sum('amount'),
            'processed_amount' => VendorPayout::where('status', 'processed')->sum('amount'),
        ];
    }

    public function getVendorEarnings($vendorId)
    {
        // Calculate vendor earnings from completed orders
        $earnings = OrderItem::where('vendor_id', $vendorId)
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->select(DB::raw('SUM(total) as total_earnings, COUNT(*) as total_orders'))
            ->first();

        // Calculate already paid amount
        $paidAmount = VendorPayout::where('vendor_id', $vendorId)
            ->where('status', 'processed')
            ->sum('amount');

        $pendingPayouts = VendorPayout::where('vendor_id', $vendorId)
            ->where('status', 'pending')
            ->sum('amount');

        return [
            'total_earnings' => $earnings->total_earnings ?? 0,
            'total_orders' => $earnings->total_orders ?? 0,
            'paid_amount' => $paidAmount,
            'pending_payouts' => $pendingPayouts,
            'balance' => ($earnings->total_earnings ?? 0) - $paidAmount - $pendingPayouts,
        ];
    }

    public function getPendingPayoutAmount()
    {
        return VendorPayout::where('status', 'pending')->sum('amount');
    }
}