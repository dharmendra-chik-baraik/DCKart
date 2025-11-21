<?php

namespace App\Interfaces;

interface PaymentRepositoryInterface
{
    public function getAllPayments($filters = []);
    public function getPaymentById($id);
    public function getPaymentsByStatus($status);
    public function updatePaymentStatus($id, $status, $transactionId = null);
    public function getPaymentStats();
    public function getRecentPayments($limit = 10);
}