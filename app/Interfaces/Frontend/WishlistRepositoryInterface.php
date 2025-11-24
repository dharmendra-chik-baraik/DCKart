<?php

namespace App\Interfaces\Frontend;

interface WishlistRepositoryInterface
{
    public function getWishlistItems($userId);
    public function addToWishlist($userId, $productId);
    public function removeFromWishlist($userId, $productId);
    public function isInWishlist($userId, $productId);
}