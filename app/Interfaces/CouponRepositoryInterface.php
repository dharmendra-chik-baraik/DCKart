<?php

namespace App\Interfaces;

interface CouponRepositoryInterface
{
    public function getAllCoupons($filters = []);
    public function getCouponById($couponId);
    public function createCoupon(array $data);
    public function updateCoupon($couponId, array $data);
    public function deleteCoupon($couponId);
    public function toggleCouponStatus($couponId);
    public function getCouponUsage($couponId);
    public function getActiveCoupons();
    public function getExpiredCoupons();
    public function validateCoupon($code, $orderAmount = null);
}