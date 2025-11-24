<?php

namespace App\Services\Frontend;

use App\Interfaces\Frontend\WishlistRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    protected $wishlistRepository;

    public function __construct(WishlistRepositoryInterface $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    public function getWishlistItems()
    {
        if (!Auth::check()) {
            throw new \Exception('Please login to view your wishlist.');
        }

        return $this->wishlistRepository->getWishlistItems(Auth::id());
    }

    public function addToWishlist($productId)
    {
        if (!Auth::check()) {
            throw new \Exception('Please login to add items to wishlist.');
        }

        return $this->wishlistRepository->addToWishlist(Auth::id(), $productId);
    }

    public function removeFromWishlist($productId)
    {
        if (!Auth::check()) {
            throw new \Exception('Please login to remove items from wishlist.');
        }

        return $this->wishlistRepository->removeFromWishlist(Auth::id(), $productId);
    }

    public function isInWishlist($productId)
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->wishlistRepository->isInWishlist(Auth::id(), $productId);
    }
}