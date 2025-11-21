<?php

namespace App\Services;

use App\Interfaces\CouponRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Models\Coupon; 
use App\Models\CouponUser; 

class CouponService
{
    protected $couponRepository;

    public function __construct(CouponRepositoryInterface $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    public function getAllCoupons($filters = [])
    {
        return $this->couponRepository->getAllCoupons($filters);
    }

    public function getCouponById($couponId)
    {
        return $this->couponRepository->getCouponById($couponId);
    }

    public function createCoupon(array $data)
    {
        try {
            $coupon = $this->couponRepository->createCoupon($data);
            
            Log::info("Coupon created: {$coupon->code}", [
                'coupon_id' => $coupon->id,
                'created_by' => auth()->id()
            ]);
            
            return $coupon;
        } catch (\Exception $e) {
            Log::error("Failed to create coupon: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateCoupon($couponId, array $data)
    {
        try {
            $coupon = $this->couponRepository->updateCoupon($couponId, $data);
            
            Log::info("Coupon updated: {$coupon->code}", [
                'coupon_id' => $coupon->id,
                'updated_by' => auth()->id()
            ]);
            
            return $coupon;
        } catch (\Exception $e) {
            Log::error("Failed to update coupon {$couponId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteCoupon($couponId)
    {
        try {
            $coupon = $this->couponRepository->getCouponById($couponId);
            $code = $coupon->code;
            
            $this->couponRepository->deleteCoupon($couponId);
            
            Log::info("Coupon deleted: {$code}", [
                'coupon_id' => $couponId,
                'deleted_by' => auth()->id()
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete coupon {$couponId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function toggleCouponStatus($couponId)
    {
        try {
            $coupon = $this->couponRepository->toggleCouponStatus($couponId);
            
            $status = $coupon->status ? 'activated' : 'deactivated';
            Log::info("Coupon {$status}: {$coupon->code}", [
                'coupon_id' => $coupon->id,
                'updated_by' => auth()->id()
            ]);
            
            return $coupon;
        } catch (\Exception $e) {
            Log::error("Failed to toggle coupon status {$couponId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function getCouponUsage($couponId)
    {
        return $this->couponRepository->getCouponUsage($couponId);
    }

    public function getCouponStats()
    {
        return [
            'total_coupons' => Coupon::count(),
            'active_coupons' => $this->couponRepository->getActiveCoupons(),
            'expired_coupons' => $this->couponRepository->getExpiredCoupons(),
            'total_usage' => CouponUser::count(),
        ];
    }

    public function validateCouponCode($code, $orderAmount = null)
    {
        return $this->couponRepository->validateCoupon($code, $orderAmount);
    }
}