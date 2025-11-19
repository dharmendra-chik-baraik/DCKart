<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariantValue;
use App\Models\Cart;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('status', true)->where('stock', '>', 0)->get();

        foreach ($customers as $customer) {
            // Add 0-5 products to cart per customer
            $cartCount = rand(0, 5);
            
            $cartProducts = $products->random($cartCount);
            
            foreach ($cartProducts as $product) {
                $variantValue = $product->variants->isNotEmpty() 
                    ? ProductVariantValue::whereIn('variant_id', $product->variants->pluck('id'))->inRandomOrder()->first()
                    : null;

                Cart::factory()->create([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                    'variant_value_id' => $variantValue?->id,
                    'quantity' => rand(1, 3),
                ]);
            }
        }

        echo "Cart items seeded: " . Cart::count() . "\n";
    }
}