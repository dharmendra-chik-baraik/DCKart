<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariantValue;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            // Create 1-5 items per order
            $itemCount = rand(1, 5);
            
            for ($i = 0; $i < $itemCount; $i++) {
                $product = Product::inRandomOrder()->first();
                $variantValue = $product->variants->isNotEmpty() 
                    ? ProductVariantValue::whereIn('variant_id', $product->variants->pluck('id'))->inRandomOrder()->first()
                    : null;

                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_value_id' => $variantValue?->id,
                    'vendor_id' => $product->vendor_id,
                    'price' => $variantValue ? $variantValue->final_price : $product->final_price,
                    'total' => ($variantValue ? $variantValue->final_price : $product->final_price) * rand(1, 3),
                ]);
            }

            // Update order totals based on items
            $order->update([
                'total_amount' => $order->items->sum('total'),
                'grand_total' => $order->items->sum('total') + $order->shipping_charge + $order->tax - $order->discount,
            ]);
        }

        echo "Order items seeded: " . OrderItem::count() . "\n";
    }
}