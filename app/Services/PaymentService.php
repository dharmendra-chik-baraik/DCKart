<?php

namespace App\Services;

use App\Interfaces\PaymentRepositoryInterface;

class PaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getAllPayments($filters = [])
    {
        return $this->paymentRepository->getAllPayments($filters);
    }

    public function getPayment($id)
    {
        return $this->paymentRepository->getPaymentById($id);
    }

    public function updatePaymentStatus($id, $status, $transactionId = null)
    {
        return $this->paymentRepository->updatePaymentStatus($id, $status, $transactionId);
    }

    public function getPaymentStats()
    {
        return $this->paymentRepository->getPaymentStats();
    }

    public function getRecentPayments($limit = 10)
    {
        return $this->paymentRepository->getRecentPayments($limit);
    }

    public function processRefund($paymentId, $amount, $reason)
    {
        // Implement refund logic here
        // This would typically integrate with your payment gateway
        $payment = $this->paymentRepository->getPaymentById($paymentId);
        
        // Update payment status to refunded
        return $this->paymentRepository->updatePaymentStatus($paymentId, 'refunded');
    }
}