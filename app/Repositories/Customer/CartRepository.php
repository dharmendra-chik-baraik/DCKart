<?php

namespace App\Repositories\Customer;

use App\Interfaces\Customer\CartRepositoryInterface;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartRepository implements CartRepositoryInterface
{
    public function getCartItems($userId)
    {
        return Cart::where('user_id', $userId)
            ->with(['product.vendor', 'variantValue.variant'])
            ->get();
    }

    public function addToCart($userId, $productId, $quantity, $variantValueId = null)
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

        // Check if item already exists in cart
        $existingCart = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('variant_value_id', $variantValueId)
            ->first();

        if ($existingCart) {
            $existingCart->quantity += $quantity;
            $existingCart->save();
            return $existingCart;
        }

        // Create new cart item
        return Cart::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'variant_value_id' => $variantValueId,
            'quantity' => $quantity
        ]);
    }

    public function updateCartItem($cartId, $quantity)
    {
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->quantity = $quantity;
        $cartItem->save();
        return $cartItem;
    }

    public function removeFromCart($cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->delete();
        return true;
    }

    public function clearCart($userId)
    {
        return Cart::where('user_id', $userId)->delete();
    }

    public function getCartItem($cartId)
    {
        return Cart::with(['product.vendor', 'variantValue.variant'])->find($cartId);
    }

    public function getCartTotal($userId)
    {
        $cartItems = $this->getCartItems($userId);
        
        $subtotal = $cartItems->sum(function($item) {
            $price = $item->variantValue ? 
                $item->product->price + $item->variantValue->price_adjustment : 
                $item->product->price;
            return $price * $item->quantity;
        });

        $tax = $subtotal * 0.18; // 18% GST
        $shipping = 0; // Free shipping for now
        $total = $subtotal + $tax + $shipping;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total
        ];
    }
}