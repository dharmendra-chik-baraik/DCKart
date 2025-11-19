<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Database\Seeder;

class OrderStatusLogSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
            $currentStatus = array_search($order->order_status, $statuses);
            
            // Create logs for each status up to current status
            for ($i = 0; $i <= $currentStatus; $i++) {
                OrderStatusLog::factory()->create([
                    'order_id' => $order->id,
                    'status' => $statuses[$i],
                    'created_at' => now()->subDays($currentStatus - $i),
                ]);
            }
        }

        echo "Order status logs seeded: " . OrderStatusLog::count() . "\n";
    }
}