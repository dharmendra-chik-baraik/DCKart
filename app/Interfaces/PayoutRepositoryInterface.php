<?php

namespace App\Interfaces;

interface PayoutRepositoryInterface
{
    public function getAllPayouts($filters = []);
    public function getPayoutById($id);
    public function getPayoutsByStatus($status);
    public function createPayout($data);
    public function updatePayoutStatus($id, $status, $transactionId = null, $remarks = null);
    public function getPayoutStats();
    public function getVendorEarnings($vendorId);
    public function getPendingPayoutAmount();
}