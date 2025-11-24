<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class CategoryFactory extends Factory
{
    private $pixabayCache = null;

    /**
     * Get category-specific image from Pixabay
     */
    private function getCategoryImage(string $categoryName): string
    {
        // Cache the API response to avoid multiple calls
        if ($this->pixabayCache === null) {
            $apiKey = config('services.pixabay.key', '53383975-ca11dd4c0362019176cbcfd48');
            
            // Map categories to relevant search terms
            $searchTerms = [
                'electronics' => 'electronics gadget technology',
                'clothing' => 'fashion clothing apparel',
                'home' => 'home decor interior',
                'sports' => 'sports fitness activity',
                'books' => 'books library reading',
                'beauty' => 'beauty cosmetics makeup',
                'toys' => 'toys games children',
                'food' => 'food cuisine cooking',
                'jewelry' => 'jewelry accessories luxury',
                'health' => 'health wellness medical',
                'automotive' => 'cars automotive vehicle',
                'garden' => 'garden plants nature'
            ];

            $searchQuery = $searchTerms[strtolower($categoryName)] ?? $categoryName;

            try {
                $response = Http::timeout(10)->get("https://pixabay.com/api/", [
                    'key' => $apiKey,
                    'q' => $searchQuery,
                    'image_type' => 'photo',
                    'per_page' => 20,
                    'safesearch' => true,
                    'category' => 'backgrounds',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $this->pixabayCache = $data['hits'] ?? [];
                }
            } catch (\Exception $e) {
                // Fallback to placeholder if API fails
                $this->pixabayCache = [];
            }
        }

        if (!empty($this->pixabayCache)) {
            $image = $this->faker->randomElement($this->pixabayCache);
            return $image['webformatURL'] ?? $image['largeImageURL'] ?? $this->getFallbackImage($categoryName);
        }

        return $this->getFallbackImage($categoryName);
    }

    /**
     * Get fallback image based on category
     */
    private function getFallbackImage(string $categoryName): string
    {
        $categoryImages = [
            'electronics' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&h=300&fit=crop',
            'clothing' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&h=300&fit=crop',
            'home' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=300&fit=crop',
            'sports' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop',
            'books' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=300&fit=crop',
            'beauty' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&h=300&fit=crop',
            'toys' => 'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?w=400&h=300&fit=crop',
            'food' => 'https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=400&h=300&fit=crop',
            'default' => 'https://picsum.photos/400/300?random=' . $this->faker->numberBetween(1, 1000)
        ];

        $key = strtolower($categoryName);
        foreach ($categoryImages as $categoryKey => $imageUrl) {
            if (str_contains($key, $categoryKey)) {
                return $imageUrl;
            }
        }

        return $categoryImages['default'];
    }

    /**
     * Get appropriate icon for category
     */
    private function getCategoryIcon(string $categoryName): string
    {
        $iconMap = [
            'electronics' => 'fas-laptop',
            'clothing' => 'fas-tshirt',
            'fashion' => 'fas-tshirt',
            'home' => 'fas-home',
            'furniture' => 'fas-couch',
            'sports' => 'fas-running',
            'books' => 'fas-book',
            'beauty' => 'fas-spa',
            'cosmetics' => 'fas-spa',
            'toys' => 'fas-gamepad',
            'food' => 'fas-utensils',
            'grocery' => 'fas-shopping-basket',
            'jewelry' => 'fas-gem',
            'health' => 'fas-heartbeat',
            'medical' => 'fas-first-aid',
            'automotive' => 'fas-car',
            'garden' => 'fas-seedling',
            'digital' => 'fas-mobile-alt',
            'music' => 'fas-music',
            'art' => 'fas-palette',
            'baby' => 'fas-baby',
            'pet' => 'fas-paw',
            'office' => 'fas-briefcase',
            'tools' => 'fas-tools'
        ];

        $key = strtolower($categoryName);
        foreach ($iconMap as $categoryKey => $icon) {
            if (str_contains($key, $categoryKey)) {
                return $icon;
            }
        }

        return $this->faker->randomElement(['fas-box', 'fas-shopping-bag', 'fas-tag']);
    }

    public function definition()
    {
        $name = $this->faker->unique()->words(2, true);
        
        return [
            'id' => Str::uuid(),
            'parent_id' => null,
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => $this->getCategoryIcon($name),
            'image' => $this->getCategoryImage($name),
            'description' => $this->faker->sentence(),
            'status' => true,
            'meta_title' => $this->faker->optional()->sentence(),
            'meta_description' => $this->faker->optional()->paragraph(),
            'meta_keywords' => $this->faker->optional()->words(5, true),
        ];
    }

    public function withParent()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => \App\Models\Category::factory(),
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => false,
            ];
        });
    }

    /**
     * Create a category with specific name and relevant image
     */
    public function forCategory(string $categoryName): static
    {
        return $this->state(function (array $attributes) use ($categoryName) {
            // Reset cache to force new category images
            $this->pixabayCache = null;
            
            return [
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'icon' => $this->getCategoryIcon($categoryName),
                'image' => $this->getCategoryImage($categoryName),
                'description' => $this->faker->sentence(),
            ];
        });
    }

    /**
     * Create main categories with common names
     */
    public function mainCategory(): static
    {
        $mainCategories = [
            'Electronics', 'Clothing & Fashion', 'Home & Garden', 
            'Sports & Outdoors', 'Books & Media', 'Beauty & Health',
            'Toys & Games', 'Food & Grocery', 'Jewelry & Accessories',
            'Automotive', 'Baby & Kids', 'Pet Supplies'
        ];

        $categoryName = $this->faker->randomElement($mainCategories);

        return $this->forCategory($categoryName);
    }

    /**
     * Create subcategories for a specific parent
     */
    public function subcategory(string $parentCategory): static
    {
        $subcategories = [
            'Electronics' => ['Smartphones', 'Laptops', 'Tablets', 'Cameras', 'Headphones', 'Smart Watches'],
            'Clothing & Fashion' => ['Men\'s Clothing', 'Women\'s Clothing', 'Kids\' Clothing', 'Shoes', 'Accessories'],
            'Home & Garden' => ['Furniture', 'Home Decor', 'Kitchen', 'Bedding', 'Garden Tools'],
            'Sports & Outdoors' => ['Fitness', 'Outdoor Gear', 'Team Sports', 'Cycling', 'Camping'],
            'Books & Media' => ['Fiction', 'Non-Fiction', 'Educational', 'Magazines', 'Audiobooks'],
            'Beauty & Health' => ['Skincare', 'Makeup', 'Hair Care', 'Vitamins', 'Personal Care']
        ];

        $availableSubs = $subcategories[$parentCategory] ?? ['Subcategory ' . $this->faker->word()];
        $subcategoryName = $this->faker->randomElement($availableSubs);

        return $this->state(function (array $attributes) use ($subcategoryName, $parentCategory) {
            // Reset cache to force new category images
            $this->pixabayCache = null;
            
            return [
                'name' => $subcategoryName,
                'slug' => Str::slug($subcategoryName),
                'icon' => $this->getCategoryIcon($subcategoryName),
                'image' => $this->getCategoryImage($subcategoryName),
                'description' => $this->faker->sentence(),
            ];
        });
    }
}