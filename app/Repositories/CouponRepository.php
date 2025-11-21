<?php

namespace App\Repositories;

use App\Interfaces\CouponRepositoryInterface;
use App\Models\Coupon;
use App\Models\CouponUser;
use Illuminate\Support\Str;

class CouponRepository implements CouponRepositoryInterface
{
    public function getAllCoupons($filters = [])
    {
        $query = Coupon::query();
        
        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where('code', 'like', '%' . $filters['search'] . '%');
        }
        
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['discount_type']) && $filters['discount_type']) {
            $query->where('discount_type', $filters['discount_type']);
        }
        
        // Order by latest
        $query->orderBy('created_at', 'desc');
        
        return $query->paginate(20);
    }

    public function getCouponById($couponId)
    {
        return Coupon::findOrFail($couponId);
    }

    public function createCoupon(array $data)
    {
        // Generate unique code if not provided
        if (empty($data['code'])) {
            $data['code'] = $this->generateUniqueCode();
        }
        
        return Coupon::create($data);
    }

    public function updateCoupon($couponId, array $data)
    {
        $coupon = $this->getCouponById($couponId);
        $coupon->update($data);
        return $coupon;
    }

    public function deleteCoupon($couponId)
    {
        $coupon = $this->getCouponById($couponId);
        return $coupon->delete();
    }

    public function toggleCouponStatus($couponId)
    {
        $coupon = $this->getCouponById($couponId);
        $coupon->update(['status' => !$coupon->status]);
        return $coupon;
    }

    public function getCouponUsage($couponId)
    {
        return CouponUser::with('user')
            ->where('coupon_id', $couponId)
            ->orderBy('used_at', 'desc')
            ->paginate(20);
    }

    public function getActiveCoupons()
    {
        return Coupon::active()->count();
    }

    public function getExpiredCoupons()
    {
        return Coupon::where('end_date', '<', now())->count();
    }

    public function validateCoupon($code, $orderAmount = null)
    {
        $coupon = Coupon::where('code', $code)->first();
        
        if (!$coupon || !$coupon->status) {
            return ['valid' => false, 'message' => 'Invalid coupon code'];
        }
        
        if ($coupon->isExpired()) {
            return ['valid' => false, 'message' => 'Coupon has expired'];
        }
        
        if ($coupon->hasReachedUsageLimit()) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached'];
        }
        
        if ($orderAmount && $coupon->min_order_value > $orderAmount) {
            return [
                'valid' => false, 
                'message' => 'Minimum order amount not met'
            ];
        }
        
        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount_amount' => $coupon->calculateDiscount($orderAmount)
        ];
    }

    private function generateUniqueCode($length = 8)
    {
        do {
            $code = Str::upper(Str::random($length));
        } while (Coupon::where('code', $code)->exists());
        
        return $code;
    }
}