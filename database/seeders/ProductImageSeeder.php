<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Create 1-4 images for each product
            $imageCount = rand(1, 4);
            
            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::factory()->create([
                    'product_id' => $product->id,
                    'position' => $i,
                ]);
            }
        }

        echo "Product images seeded: " . ProductImage::count() . "\n";
    }
}