<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaymentUpdateRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'payment_method' => $request->get('payment_method'),
            'search' => $request->get('search'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $payments = $this->paymentService->getAllPayments($filters);
        $stats = $this->paymentService->getPaymentStats();

        return view('admin.payments.index', compact('payments', 'stats', 'filters'));
    }

    public function show($id)
    {
        $payment = $this->paymentService->getPayment($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function updateStatus(PaymentUpdateRequest $request, $id)
    {
        try {
            $payment = $this->paymentService->updatePaymentStatus(
                $id, 
                $request->payment_status, 
                $request->transaction_id
            );

            return redirect()->route('admin.payments.show', $id)
                ->with('success', 'Payment status updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update payment status: ' . $e->getMessage());
        }
    }

    public function processRefund(Request $request, $id)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01',
            'refund_reason' => 'required|string|max:500',
        ]);

        try {
            $payment = $this->paymentService->processRefund(
                $id,
                $request->refund_amount,
                $request->refund_reason
            );

            return redirect()->route('admin.payments.show', $id)
                ->with('success', 'Refund processed successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id',
            'payment_status' => 'required|in:pending,completed,failed,refunded',
        ]);

        try {
            $updatedCount = 0;
            foreach ($request->payment_ids as $paymentId) {
                $this->paymentService->updatePaymentStatus($paymentId, $request->payment_status);
                $updatedCount++;
            }

            return redirect()->route('admin.payments.index')
                ->with('success', "Successfully updated {$updatedCount} payments.");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update payments: ' . $e->getMessage());
        }
    }
}