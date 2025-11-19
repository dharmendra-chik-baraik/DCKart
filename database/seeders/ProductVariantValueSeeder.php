<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use Illuminate\Database\Seeder;

class ProductVariantValueSeeder extends Seeder
{
    public function run(): void
    {
        $variants = ProductVariant::all();

        foreach ($variants as $variant) {
            if ($variant->name === 'Color') {
                $colors = ['Red', 'Blue', 'Green', 'Black', 'White'];
                foreach ($colors as $color) {
                    ProductVariantValue::factory()->create([
                        'variant_id' => $variant->id,
                        'value' => $color,
                        'price_adjustment' => rand(-100, 100),
                    ]);
                }
            } elseif ($variant->name === 'Size') {
                $sizes = ['S', 'M', 'L', 'XL'];
                foreach ($sizes as $size) {
                    ProductVariantValue::factory()->create([
                        'variant_id' => $variant->id,
                        'value' => $size,
                        'price_adjustment' => rand(-50, 50),
                    ]);
                }
            } elseif ($variant->name === 'Storage') {
                $storages = ['64GB', '128GB', '256GB'];
                foreach ($storages as $storage) {
                    ProductVariantValue::factory()->create([
                        'variant_id' => $variant->id,
                        'value' => $storage,
                        'price_adjustment' => rand(500, 1500),
                    ]);
                }
            }
        }

        echo "Product variant values seeded: " . ProductVariantValue::count() . "\n";
    }
}