<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // Get all vendor users
        $vendorUsers = User::where('role', 'vendor')->get();

        foreach ($vendorUsers as $user) {
            VendorProfile::factory()->create([
                'user_id' => $user->id,
                'status' => fake()->randomElement(['approved', 'pending']),
            ]);
        }

        // Create some additional approved vendors
        VendorProfile::factory(5)->approved()->create();

        echo "Vendor profiles seeded: " . VendorProfile::count() . "\n";
    }
}