<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\VendorProfile;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $approvedVendors = VendorProfile::where('status', 'approved')->get();
        $categories = Category::whereNotNull('parent_id')->get();

        // Create products for each approved vendor
        foreach ($approvedVendors as $vendor) {
            // Create 3-8 products per vendor
            $productCount = rand(3, 8);
            
            Product::factory($productCount)->create([
                'vendor_id' => $vendor->id,
                'category_id' => $categories->random()->id,
            ]);
        }

        // Create some featured products
        Product::factory(15)->featured()->create([
            'vendor_id' => $approvedVendors->random()->id,
            'category_id' => $categories->random()->id,
        ]);

        // Create some products with discounts
        Product::factory(20)->withDiscount()->create([
            'vendor_id' => $approvedVendors->random()->id,
            'category_id' => $categories->random()->id,
        ]);

        echo "Products seeded: " . Product::count() . "\n";
    }
}