<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\WishlistRepositoryInterface;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function getWishlistItems($userId)
    {
        return Wishlist::where('user_id', $userId)
            ->with('product.vendor')
            ->get();
    }

    public function addToWishlist($userId, $productId)
    {
        // Check if product exists and is available
        $product = Product::where('id', $productId)
            ->where('status', true)
            ->whereHas('vendor', function($q) {
                $q->where('status', 'approved');
            })
            ->first();

        if (!$product) {
            throw new \Exception('Product not found or unavailable.');
        }

        // Check if already in wishlist
        $existingWishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingWishlist) {
            return $existingWishlist;
        }

        return Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    public function removeFromWishlist($userId, $productId)
    {
        return Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();
    }

    public function isInWishlist($userId, $productId)
    {
        return Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }
}