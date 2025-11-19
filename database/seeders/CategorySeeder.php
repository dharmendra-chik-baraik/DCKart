<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $mainCategories = [
            [
                'name' => 'Electronics',
                'icon' => 'fas-tv',
                'description' => 'Latest gadgets and electronic devices',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Fashion',
                'icon' => 'fas-tshirt',
                'description' => 'Trendy clothing and accessories',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Home & Kitchen',
                'icon' => 'fas-home',
                'description' => 'Everything for your home and kitchen',
                'image' => 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Beauty & Health',
                'icon' => 'fas-heart',
                'description' => 'Beauty products and health supplements',
                'image' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Sports & Fitness',
                'icon' => 'fas-running',
                'description' => 'Sports equipment and fitness gear',
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Books & Stationery',
                'icon' => 'fas-book',
                'description' => 'Books, notebooks and office supplies',
                'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=300&fit=crop'
            ],
        ];

        foreach ($mainCategories as $category) {
            Category::create([
                'id' => Str::uuid(),
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
                'image' => $category['image'],
                'description' => $category['description'],
                'status' => true,
            ]);
        }

        // Create subcategories for Electronics
        $electronics = Category::where('name', 'Electronics')->first();
        $electronicsSubcategories = [
            ['name' => 'Smartphones', 'description' => 'Latest smartphones and accessories'],
            ['name' => 'Laptops', 'description' => 'Laptops, ultrabooks and notebooks'],
            ['name' => 'Tablets', 'description' => 'Tablets and e-readers'],
            ['name' => 'Cameras', 'description' => 'DSLR, mirrorless and point-and-shoot cameras'],
            ['name' => 'Headphones', 'description' => 'Wireless and wired headphones'],
            ['name' => 'Smart Watches', 'description' => 'Smart watches and fitness trackers'],
        ];

        foreach ($electronicsSubcategories as $subcategory) {
            Category::create([
                'id' => Str::uuid(),
                'parent_id' => $electronics->id,
                'name' => $subcategory['name'],
                'slug' => Str::slug($subcategory['name']),
                'icon' => 'fas-mobile-alt',
                'description' => $subcategory['description'],
                'status' => true,
            ]);
        }

        // Create subcategories for Fashion
        $fashion = Category::where('name', 'Fashion')->first();
        $fashionSubcategories = [
            ['name' => "Men's Clothing", 'description' => 'Clothing for men'],
            ['name' => "Women's Clothing", 'description' => 'Clothing for women'],
            ['name' => 'Footwear', 'description' => 'Shoes and sandals'],
            ['name' => 'Accessories', 'description' => 'Bags, watches and jewelry'],
        ];

        foreach ($fashionSubcategories as $subcategory) {
            Category::create([
                'id' => Str::uuid(),
                'parent_id' => $fashion->id,
                'name' => $subcategory['name'],
                'slug' => Str::slug($subcategory['name']),
                'icon' => 'fas-tshirt',
                'description' => $subcategory['description'],
                'status' => true,
            ]);
        }

        echo "Categories seeded: " . Category::count() . "\n";
    }
}