<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            Payment::factory()->create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'amount' => $order->grand_total,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'transaction_id' => $order->payment_status === 'completed'
                    ? 'TXN' . rand(1000000000, 9999999999) 
                    : null,
            ]);
        }

        echo "Payments seeded: " . Payment::count() . "\n";
    }
}