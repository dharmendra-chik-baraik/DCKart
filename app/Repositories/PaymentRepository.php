<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function getAllPayments($filters = [])
    {
        $query = Payment::with(['order', 'user'])
            ->orderBy('created_at', 'desc');

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('payment_status', $filters['status']);
        }

        if (isset($filters['payment_method']) && $filters['payment_method'] !== '') {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('order_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
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

    public function getPaymentById($id)
    {
        return Payment::with(['order', 'user', 'order.items', 'order.items.product'])
            ->findOrFail($id);
    }

    public function getPaymentsByStatus($status)
    {
        return Payment::with(['order', 'user'])
            ->where('payment_status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function updatePaymentStatus($id, $status, $transactionId = null)
    {
        $payment = Payment::findOrFail($id);
        
        $updateData = ['payment_status' => $status];
        if ($transactionId) {
            $updateData['transaction_id'] = $transactionId;
        }

        $payment->update($updateData);

        // Update order payment status if payment is completed
        if ($status === 'completed' && $payment->order) {
            $payment->order->update(['payment_status' => 'completed']);
        }

        return $payment;
    }

    public function getPaymentStats()
    {
        return [
            'total' => Payment::count(),
            'completed' => Payment::where('payment_status', 'completed')->count(),
            'pending' => Payment::where('payment_status', 'pending')->count(),
            'failed' => Payment::where('payment_status', 'failed')->count(),
            'refunded' => Payment::where('payment_status', 'refunded')->count(),
            'total_amount' => Payment::where('payment_status', 'completed')->sum('amount'),
            'pending_amount' => Payment::where('payment_status', 'pending')->sum('amount'),
        ];
    }

    public function getRecentPayments($limit = 10)
    {
        return Payment::with(['order', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}