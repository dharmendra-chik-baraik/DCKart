<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\VendorRepositoryInterface;
use App\Models\VendorProfile as Vendor;
use App\Models\Product;

class VendorRepository implements VendorRepositoryInterface
{
    public function getAllVendors($perPage = 12)
    {
        return Vendor::where('status', 'approved')
            ->whereHas('user', function($query) {
                $query->where('status', 'active');
            })
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->paginate($perPage);
    }

    public function getVendorBySlug($slug)
    {
        return Vendor::where('shop_slug', $slug)
            ->where('status', 'approved')
            ->whereHas('user', function($query) {
                $query->where('status', 'active');
            })
            ->withCount('products')
            ->firstOrFail();
    }

    public function getVendorProducts($vendorId, $perPage = 12)
    {
        return Product::where('vendor_id', $vendorId)
            ->where('status', true)
            ->with('category')
            ->paginate($perPage);
    }
}