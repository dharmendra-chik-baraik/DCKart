<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $vendors = VendorProfile::where('status', 'approved')->get();

        foreach ($customers as $customer) {
            // Create 0-5 orders per customer
            $orderCount = rand(0, 5);
            
            Order::factory($orderCount)->create([
                'user_id' => $customer->id,
                'vendor_id' => $vendors->random()->id,
            ]);
        }

        echo "Orders seeded: " . Order::count() . "\n";
    }
}