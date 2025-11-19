<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketMessageSeeder extends Seeder
{
    public function run(): void
    {
        $tickets = Ticket::all();
        $admin = User::where('role', 'admin')->first();

        foreach ($tickets as $ticket) {
            // Create 2-8 messages per ticket
            $messageCount = rand(2, 8);
            
            for ($i = 0; $i < $messageCount; $i++) {
                // Alternate between customer and admin/vendor
                $sender = $i % 2 === 0 
                    ? $ticket->user_id 
                    : ($ticket->vendor_id ? $ticket->vendor->user_id : $admin->id);

                TicketMessage::factory()->create([
                    'ticket_id' => $ticket->id,
                    'sender_id' => $sender,
                    'created_at' => now()->subHours($messageCount - $i),
                ]);
            }
        }

        echo "Ticket messages seeded: " . TicketMessage::count() . "\n";
    }
}