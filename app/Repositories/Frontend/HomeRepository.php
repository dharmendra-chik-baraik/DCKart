<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\HomeRepositoryInterface;
use App\Models\Product;
use App\Models\Category;
use App\Models\VendorProfile as Vendor;
use App\Models\Page;

class HomeRepository implements HomeRepositoryInterface
{
    public function getFeaturedProducts($limit = 8)
    {
        return Product::where('status', true)
            ->where('is_featured', true)
            ->whereHas('vendor', function($q) {
                $q->where('status', 'approved');
            })
            ->with('vendor')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function getPopularCategories($limit = 8)
    {
        return Category::where('status', true)
            ->whereNull('parent_id')
            ->withCount(['products' => function($query) {
                $query->where('status', true)
                    ->whereHas('vendor', function($q) {
                        $q->where('status', 'approved');
                    });
            }])
            ->orderBy('products_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopVendors($limit = 6)
    {
        return Vendor::where('status', 'approved')
            ->whereHas('user', function($query) {
                $query->where('status', 'active');
            })
            ->withCount(['products' => function($query) {
                $query->where('status', true);
            }])
            ->orderBy('products_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getActivePages()
    {
        return Page::where('status', true)
            ->orderBy('title')
            ->get();
    }
}