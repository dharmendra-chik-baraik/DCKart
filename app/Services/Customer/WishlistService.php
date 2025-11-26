<?php

namespace App\Services\Customer;

use App\Interfaces\Customer\WishlistRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class WishlistService
{
    protected $wishlistRepository;

    public function __construct(WishlistRepositoryInterface $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    public function getWishlistItems(): Collection
    {
        if (!Auth::check()) {
            throw new \Exception('Please login to view your wishlist.');
        }

        return $this->wishlistRepository->getWishlistItems(Auth::id());
    }

    public function getUserWishlist(): Paginator
    {
        if (!Auth::check()) {
            throw new \Exception('Please login to view your wishlist.');
        }

        return $this->wishlistRepository->getUserWishlist(Auth::id());
    }

    public function getUserWishlistCount(): int
    {
        if (!Auth::check()) {
            return 0;
        }

        return $this->wishlistRepository->getUserWishlistCount(Auth::id());
    }

    public function addToWishlist(string $productId): bool
    {
        if (!Auth::check()) {
            throw new \Exception('Please login to add items to wishlist.');
        }

        return $this->wishlistRepository->addToWishlist(Auth::id(), $productId);
    }

    public function removeFromWishlist(string $productId): bool
    {
        if (!Auth::check()) {
            throw new \Exception('Please login to remove items from wishlist.');
        }

        return $this->wishlistRepository->removeFromWishlist(Auth::id(), $productId);
    }

    public function clearWishlist(): bool
    {
        if (!Auth::check()) {
            throw new \Exception('Please login to clear your wishlist.');
        }

        return $this->wishlistRepository->clearWishlist(Auth::id());
    }

    public function isInWishlist(string $productId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->wishlistRepository->isInWishlist(Auth::id(), $productId);
    }

    public function moveToCart(string $productId): bool
    {
        if (!Auth::check()) {
            throw new \Exception('Please login to move items to cart.');
        }

        return $this->wishlistRepository->moveToCart(Auth::id(), $productId);
    }
}