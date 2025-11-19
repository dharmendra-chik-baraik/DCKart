<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // Create percentage discount coupons
        Coupon::factory(5)->percentageDiscount()->create();
        
        // Create fixed discount coupons
        Coupon::factory(5)->fixedDiscount()->create();
        
        // Create some expired coupons
        Coupon::factory(2)->expired()->create();
        
        // Create some upcoming coupons
        Coupon::factory(2)->upcoming()->create();
        
        // Create some inactive coupons
        Coupon::factory(2)->inactive()->create();

        echo "Coupons seeded: " . Coupon::count() . "\n";
    }
}