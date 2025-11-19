<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();

        foreach ($customers as $customer) {
            // Create 1-3 addresses per customer
            $addressCount = rand(1, 3);
            
            UserAddress::factory($addressCount)->create([
                'user_id' => $customer->id,
            ]);

            // Set one address as default
            $customer->addresses()->inRandomOrder()->first()?->update(['is_default' => true]);
        }

        echo "User addresses seeded: " . UserAddress::count() . "\n";
    }
}