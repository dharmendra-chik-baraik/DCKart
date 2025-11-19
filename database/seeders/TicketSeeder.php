<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VendorProfile;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $vendors = VendorProfile::where('status', 'approved')->get();

        // Create customer support tickets
        foreach ($customers as $customer) {
            // Create 0-2 tickets per customer
            $ticketCount = rand(0, 2);
            
            Ticket::factory($ticketCount)->create([
                'user_id' => $customer->id,
                'vendor_id' => null,
            ]);
        }

        // Create vendor-related tickets
        foreach ($vendors as $vendor) {
            // Create 0-3 tickets per vendor
            $ticketCount = rand(0, 3);
            
            Ticket::factory($ticketCount)->create([
                'user_id' => $vendor->user_id,
                'vendor_id' => $vendor->id,
            ]);
        }

        echo "Tickets seeded: " . Ticket::count() . "\n";
    }
}