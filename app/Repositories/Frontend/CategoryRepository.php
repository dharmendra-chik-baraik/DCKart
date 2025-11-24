<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories()
    {
        return Category::where('status', true)
            ->whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->where('status', true);
            }])
            ->get();
    }

    public function getCategoryBySlug($slug)
    {
        return Category::where('slug', $slug)
            ->where('status', true)
            ->with(['children' => function($query) {
                $query->where('status', true);
            }])
            ->firstOrFail();
    }

    public function getCategoryWithProducts($slug, $perPage = 12)
    {
        $category = $this->getCategoryBySlug($slug);
        
        $products = $category->products()
            ->where('status', true)
            ->whereHas('vendor', function($q) {
                $q->where('status', 'approved');
            })
            ->with('vendor')
            ->paginate($perPage);

        return [
            'category' => $category,
            'products' => $products
        ];
    }
}