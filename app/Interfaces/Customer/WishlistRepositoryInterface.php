<?php

namespace App\Interfaces\Customer;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface WishlistRepositoryInterface
{
    public function getWishlistItems($userId): Collection;

    public function getUserWishlist(string $userId): Paginator;

    public function getUserWishlistCount(string $userId): int;

    public function addToWishlist(string $userId, string $productId): bool;

    public function removeFromWishlist(string $userId, string $productId): bool;

    public function clearWishlist(string $userId): bool;

    public function isInWishlist(string $userId, string $productId): bool;

    public function moveToCart(string $userId, string $productId): bool;
}