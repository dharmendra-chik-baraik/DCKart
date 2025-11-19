<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('status', true)->get();

        foreach ($customers as $customer) {
            // Add 0-8 products to wishlist per customer
            $wishlistCount = rand(0, 8);
            
            $wishlistProducts = $products->random($wishlistCount);
            
            foreach ($wishlistProducts as $product) {
                Wishlist::factory()->create([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                ]);
            }
        }

        echo "Wishlist items seeded: " . Wishlist::count() . "\n";
    }
}