<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\User;
use App\Models\CouponUser;
use Illuminate\Database\Seeder;

class CouponUserSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = Coupon::where('status', true)->get();
        $customers = User::where('role', 'customer')->get();

        foreach ($coupons as $coupon) {
            // Mark coupon as used by 0-5 customers
            $usageCount = rand(0, min(5, $coupon->usage_limit ?? 5));
            
            $users = $customers->random($usageCount);
            
            foreach ($users as $user) {
                CouponUser::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'used_at' => fake()->dateTimeThisYear(),
                ]);

                // Update coupon used count
                $coupon->increment('used_count');
            }
        }

        echo "Coupon users seeded: " . CouponUser::count() . "\n";
    }
}