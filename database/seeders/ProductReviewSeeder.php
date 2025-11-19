<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('status', true)->get();

        foreach ($products as $product) {
            // Create 0-8 reviews per product
            $reviewCount = rand(0, 8);
            
            $reviewers = $customers->random($reviewCount);
            
            foreach ($reviewers as $reviewer) {
                ProductReview::factory()->create([
                    'user_id' => $reviewer->id,
                    'product_id' => $product->id,
                    'status' => fake()->randomElement(['approved', 'pending']),
                ]);
            }
        }

        echo "Product reviews seeded: " . ProductReview::count() . "\n";
    }
}