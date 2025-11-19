<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::inRandomOrder()->take(50)->get();

        foreach ($products as $product) {
            // 30% chance to have color variant
            if (rand(1, 100) <= 30) {
                ProductVariant::factory()->color()->create([
                    'product_id' => $product->id,
                ]);
            }

            // 20% chance to have size variant
            if (rand(1, 100) <= 20) {
                ProductVariant::factory()->size()->create([
                    'product_id' => $product->id,
                ]);
            }

            // 10% chance to have storage variant
            if (rand(1, 100) <= 10) {
                ProductVariant::factory()->storage()->create([
                    'product_id' => $product->id,
                ]);
            }
        }

        echo "Product variants seeded: " . ProductVariant::count() . "\n";
    }
}