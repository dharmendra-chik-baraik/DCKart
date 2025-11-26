<?php

namespace App\Interfaces\Customer;

interface CartRepositoryInterface
{
    public function getCartItems($userId);
    public function addToCart($userId, $productId, $quantity, $variantValueId = null);
    public function updateCartItem($cartId, $quantity);
    public function removeFromCart($cartId);
    public function clearCart($userId);
    public function getCartItem($cartId);
    public function getCartTotal($userId);
}