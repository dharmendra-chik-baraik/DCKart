<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\Category;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts($filters = [], $sort = 'latest', $perPage = 12)
    {
        $query = Product::where('status', true)
            ->whereHas('vendor', function($q) {
                $q->where('status', 'approved');
            })
            ->with('vendor', 'category');

        // Apply filters
        if (isset($filters['category']) && $filters['category']) {
            $query->whereHas('category', function($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }

        if (isset($filters['min_price']) && $filters['min_price']) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price']) && $filters['max_price']) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Apply sorting
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage);
    }

    public function getProductBySlug($slug)
    {
        return Product::where('slug', $slug)
            ->where('status', true)
            ->whereHas('vendor', function($q) {
                $q->where('status', 'approved');
            })
            ->with(['vendor', 'category', 'reviews.user', 'images', 'variants.values'])
            ->firstOrFail();
    }

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

    public function getProductsByCategory($categoryId, $perPage = 12)
    {
        return Product::where('category_id', $categoryId)
            ->where('status', true)
            ->whereHas('vendor', function($q) {
                $q->where('status', 'approved');
            })
            ->with('vendor')
            ->paginate($perPage);
    }

    public function getRelatedProducts($productId, $categoryId, $limit = 4)
    {
        return Product::where('category_id', $categoryId)
            ->where('id', '!=', $productId)
            ->where('status', true)
            ->whereHas('vendor', function($q) {
                $q->where('status', 'approved');
            })
            ->with('vendor')
            ->take($limit)
            ->get();
    }
}