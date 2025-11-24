<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\SearchRepositoryInterface;
use App\Models\Product;
use App\Models\Category;
use App\Models\VendorProfile as Vendor;

class SearchRepository implements SearchRepositoryInterface
{
    public function searchProducts($query, $perPage = 12)
    {
        return Product::where('status', true)
            ->whereHas('vendor', function($q) {
                $q->where('status', 'approved');
            })
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('short_description', 'LIKE', "%{$query}%")
                  ->orWhereHas('category', function($q) use ($query) {
                      $q->where('name', 'LIKE', "%{$query}%");
                  })
                  ->orWhereHas('vendor', function($q) use ($query) {
                      $q->where('shop_name', 'LIKE', "%{$query}%");
                  });
            })
            ->with('vendor', 'category')
            ->paginate($perPage);
    }

    public function searchCategories($query, $limit = 5)
    {
        return Category::where('status', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->take($limit)
            ->get();
    }

    public function searchVendors($query, $limit = 5)
    {
        return Vendor::where('status', 'approved')
            ->whereHas('user', function($query) {
                $query->where('status', 'active');
            })
            ->where('shop_name', 'LIKE', "%{$query}%")
            ->take($limit)
            ->get();
    }
}