<?php

namespace Database\Seeders;

use App\Models\VendorProfile;
use App\Models\VendorPayout;
use Illuminate\Database\Seeder;

class VendorPayoutSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = VendorProfile::where('status', 'approved')->get();

        foreach ($vendors as $vendor) {
            // Create 0-3 payouts per vendor
            $payoutCount = rand(0, 3);
            
            VendorPayout::factory($payoutCount)->create([
                'vendor_id' => $vendor->id,
            ]);
        }

        echo "Vendor payouts seeded: " . VendorPayout::count() . "\n";
    }
}