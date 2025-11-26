<?php

namespace App\Repositories\Customer;

use App\Interfaces\Customer\WishlistRepositoryInterface;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function getWishlistItems($userId): Collection
    {
        return Wishlist::where('user_id', $userId)
            ->with('product.vendor')
            ->get();
    }

    public function getUserWishlist(string $userId): Paginator
    {
        return Wishlist::where('user_id', $userId)
            ->with(['product.images', 'product.vendor'])
            ->latest()
            ->paginate(12);
    }

    public function getUserWishlistCount(string $userId): int
    {
        return Wishlist::where('user_id', $userId)->count();
    }

    public function addToWishlist(string $userId, string $productId): bool
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
            return true; // Already exists, consider it as success
        }

        try {
            Wishlist::create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'user_id' => $userId,
                'product_id' => $productId,
                'created_at' => now(),
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to add to wishlist: ' . $e->getMessage());
            return false;
        }
    }

    public function removeFromWishlist(string $userId, string $productId): bool
    {
        return Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete() > 0;
    }

    public function clearWishlist(string $userId): bool
    {
        return Wishlist::where('user_id', $userId)->delete() > 0;
    }

    public function isInWishlist(string $userId, string $productId): bool
    {
        return Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }

    public function moveToCart(string $userId, string $productId): bool
    {
        return DB::transaction(function () use ($userId, $productId) {
            // Check if product exists and is available
            $product = Product::where('id', $productId)
                ->where('status', 1)
                ->where('stock', '>', 0)
                ->first();

            if (!$product) {
                return false;
            }

            // Add to cart
            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                Cart::create([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => 1,
                ]);
            }

            // Remove from wishlist
            $this->removeFromWishlist($userId, $productId);

            return true;
        });
    }
}