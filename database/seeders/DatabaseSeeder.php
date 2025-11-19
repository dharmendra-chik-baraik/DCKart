<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            VendorSeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            ProductVariantSeeder::class,
            ProductVariantValueSeeder::class,
            UserAddressSeeder::class,
            ShippingMethodSeeder::class,
            CouponSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            OrderStatusLogSeeder::class,
            PaymentSeeder::class,
            VendorPayoutSeeder::class,
            WishlistSeeder::class,
            CartSeeder::class,
            ProductReviewSeeder::class,
            TicketSeeder::class,
            TicketMessageSeeder::class,
            PageSeeder::class,
            CouponUserSeeder::class,
            ActivityLogSeeder::class,
            NotificationSeeder::class,
        ]);

        echo "All seeders completed successfully!\n";
    }
}