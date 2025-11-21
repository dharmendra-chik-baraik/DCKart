<?php

namespace App\Services;

use App\Interfaces\PayoutRepositoryInterface;
use App\Models\VendorProfile;

class PayoutService
{
    protected $payoutRepository;

    public function __construct(PayoutRepositoryInterface $payoutRepository)
    {
        $this->payoutRepository = $payoutRepository;
    }

    public function getAllPayouts($filters = [])
    {
        return $this->payoutRepository->getAllPayouts($filters);
    }

    public function getPayout($id)
    {
        return $this->payoutRepository->getPayoutById($id);
    }

    public function createPayout($data)
    {
        return $this->payoutRepository->createPayout($data);
    }

    public function updatePayoutStatus($id, $status, $transactionId = null, $remarks = null)
    {
        return $this->payoutRepository->updatePayoutStatus($id, $status, $transactionId, $remarks);
    }

    public function getPayoutStats()
    {
        return $this->payoutRepository->getPayoutStats();
    }

    public function getVendorEarnings($vendorId)
    {
        return $this->payoutRepository->getVendorEarnings($vendorId);
    }

    public function processBulkPayouts($payoutIds, $status, $transactionId = null)
    {
        $results = [];
        foreach ($payoutIds as $payoutId) {
            try {
                $payout = $this->payoutRepository->updatePayoutStatus($payoutId, $status, $transactionId);
                $results[] = [
                    'id' => $payoutId,
                    'status' => 'success',
                    'payout' => $payout
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'id' => $payoutId,
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        }
        return $results;
    }

    public function generatePayoutForVendor($vendorId, $amount, $remarks = null)
    {
        $vendor = VendorProfile::findOrFail($vendorId);
        
        $payoutData = [
            'vendor_id' => $vendorId,
            'amount' => $amount,
            'status' => 'pending',
            'remarks' => $remarks
        ];

        return $this->payoutRepository->createPayout($payoutData);
    }

    public function getVendorsWithEarnings()
    {
        $vendors = VendorProfile::with(['user'])->where('status', 'approved')->get();
        
        $vendorsWithEarnings = [];
        foreach ($vendors as $vendor) {
            $earnings = $this->getVendorEarnings($vendor->id);
            if ($earnings['balance'] > 0) {
                $vendorsWithEarnings[] = [
                    'vendor' => $vendor,
                    'earnings' => $earnings
                ];
            }
        }

        return $vendorsWithEarnings;
    }
}