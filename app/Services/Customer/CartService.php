<?php

namespace App\Services\Customer;

use App\Interfaces\Customer\CartRepositoryInterface;
use App\Models\Product;
use App\Models\ProductVariantValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getCartData()
    {
        if (!Auth::check()) {
            return $this->getGuestCartData();
        }

        $cartItems = $this->cartRepository->getCartItems(Auth::id());
        $totals = $this->cartRepository->getCartTotal(Auth::id());

        return [
            'cartItems' => $cartItems,
            'subtotal' => $totals['subtotal'],
            'tax' => $totals['tax'],
            'shipping' => $totals['shipping'],
            'total' => $totals['total']
        ];
    }

    public function addToCart($productId, $quantity, $variantValueId = null)
    {
        if (!Auth::check()) {
            return $this->addToGuestCart($productId, $quantity, $variantValueId);
        }

        return $this->cartRepository->addToCart(Auth::id(), $productId, $quantity, $variantValueId);
    }

    public function updateCartItem($cartId, $quantity)
    {
        if (!Auth::check()) {
            return $this->updateGuestCartItem($cartId, $quantity);
        }

        return $this->cartRepository->updateCartItem($cartId, $quantity);
    }

    public function removeFromCart($cartId)
    {
        if (!Auth::check()) {
            return $this->removeFromGuestCart($cartId);
        }

        return $this->cartRepository->removeFromCart($cartId);
    }

    public function clearCart()
    {
        if (!Auth::check()) {
            return $this->clearGuestCart();
        }

        return $this->cartRepository->clearCart(Auth::id());
    }

    public function getCartCount()
    {
        if (!Auth::check()) {
            $cartData = session()->get('cart', []);
            return count($cartData);
        }

        return $this->cartRepository->getCartItems(Auth::id())->count();
    }

    public function mergeGuestCartWithUser($userId)
    {
        $guestCart = session()->get('cart', []);
        
        if (empty($guestCart)) {
            return true;
        }

        foreach ($guestCart as $item) {
            try {
                $this->cartRepository->addToCart(
                    $userId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['variant_value_id'] ?? null
                );
            } catch (\Exception $e) {
                // Log error and continue with other items
                \Log::error('Failed to merge cart item: ' . $e->getMessage());
            }
        }

        // Clear guest cart after merging
        session()->forget('cart');
        
        return true;
    }

    // Guest cart methods (using session)
    private function getGuestCartData()
    {
        $cartData = session()->get('cart', []);
        $cartItems = collect();

        foreach ($cartData as $sessionId => $item) {
            // Load product data for each cart item
            $product = Product::where('id', $item['product_id'])
                ->where('status', true)
                ->whereHas('vendor', function($q) {
                    $q->where('status', 'approved');
                })
                ->with('vendor')
                ->first();

            if (!$product) {
                // Remove invalid product from cart
                $this->removeFromGuestCart($sessionId);
                continue;
            }

            $variantValue = null;
            if (!empty($item['variant_value_id'])) {
                $variantValue = ProductVariantValue::with('variant')
                    ->find($item['variant_value_id']);
            }

            $cartItems->push((object)[
                'id' => $sessionId,
                'product' => $product,
                'quantity' => $item['quantity'],
                'variant_value_id' => $item['variant_value_id'] ?? null,
                'variantValue' => $variantValue
            ]);
        }

        $subtotal = $cartItems->sum(function($item) {
            $price = $item->variantValue ? 
                $item->product->price + $item->variantValue->price_adjustment : 
                $item->product->price;
            return $price * $item->quantity;
        });

        $tax = $subtotal * 0.18;
        $shipping = 0;
        $total = $subtotal + $tax + $shipping;

        return [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total
        ];
    }

    private function addToGuestCart($productId, $quantity, $variantValueId = null)
    {
        // Validate product exists and is available
        $product = Product::where('id', $productId)
            ->where('status', true)
            ->whereHas('vendor', function($q) {
                $q->where('status', 'approved');
            })
            ->first();

        if (!$product) {
            throw new \Exception('Product not found or unavailable.');
        }

        // Validate variant if provided
        if ($variantValueId) {
            $variantValue = ProductVariantValue::where('id', $variantValueId)
                ->whereHas('variant', function($q) use ($productId) {
                    $q->where('product_id', $productId);
                })
                ->first();

            if (!$variantValue) {
                throw new \Exception('Invalid product variant selected.');
            }

            // Check stock for variant
            if ($variantValue->stock < $quantity) {
                throw new \Exception('Insufficient stock for selected variant.');
            }
        } else {
            // Check stock for main product
            if ($product->stock < $quantity) {
                throw new \Exception('Insufficient stock for this product.');
            }
        }

        $cartData = session()->get('cart', []);
        $sessionId = $this->generateSessionId($productId, $variantValueId);

        // Check if item already exists in cart
        if (isset($cartData[$sessionId])) {
            $cartData[$sessionId]['quantity'] += $quantity;
        } else {
            $cartData[$sessionId] = [
                'session_id' => $sessionId,
                'product_id' => $productId,
                'variant_value_id' => $variantValueId,
                'quantity' => $quantity,
                'added_at' => now()->toDateTimeString()
            ];
        }

        session()->put('cart', $cartData);
        
        return [
            'session_id' => $sessionId,
            'product' => $product,
            'quantity' => $cartData[$sessionId]['quantity'],
            'variant_value_id' => $variantValueId
        ];
    }

    private function updateGuestCartItem($sessionId, $quantity)
    {
        $cartData = session()->get('cart', []);

        if (!isset($cartData[$sessionId])) {
            throw new \Exception('Cart item not found.');
        }

        if ($quantity <= 0) {
            return $this->removeFromGuestCart($sessionId);
        }

        // Validate stock
        $item = $cartData[$sessionId];
        $product = Product::find($item['product_id']);
        
        if (!$product) {
            $this->removeFromGuestCart($sessionId);
            throw new \Exception('Product no longer available.');
        }

        if ($item['variant_value_id']) {
            $variantValue = ProductVariantValue::find($item['variant_value_id']);
            if ($variantValue && $variantValue->stock < $quantity) {
                throw new \Exception('Insufficient stock for selected variant.');
            }
        } else {
            if ($product->stock < $quantity) {
                throw new \Exception('Insufficient stock for this product.');
            }
        }

        $cartData[$sessionId]['quantity'] = $quantity;
        $cartData[$sessionId]['updated_at'] = now()->toDateTimeString();
        
        session()->put('cart', $cartData);

        return [
            'session_id' => $sessionId,
            'quantity' => $quantity
        ];
    }

    private function removeFromGuestCart($sessionId)
    {
        $cartData = session()->get('cart', []);

        if (isset($cartData[$sessionId])) {
            unset($cartData[$sessionId]);
            session()->put('cart', $cartData);
            return true;
        }

        throw new \Exception('Cart item not found.');
    }

    private function clearGuestCart()
    {
        session()->forget('cart');
        return true;
    }

    private function generateSessionId($productId, $variantValueId = null)
    {
        return md5($productId . '_' . ($variantValueId ?? ''));
    }

    public function validateGuestCart()
    {
        $cartData = session()->get('cart', []);
        $validCartData = [];
        $removedItems = [];

        foreach ($cartData as $sessionId => $item) {
            $product = Product::where('id', $item['product_id'])
                ->where('status', true)
                ->whereHas('vendor', function($q) {
                    $q->where('status', 'approved');
                })
                ->first();

            if (!$product) {
                $removedItems[] = $item['product_id'];
                continue;
            }

            // Validate variant if exists
            if ($item['variant_value_id']) {
                $variantValue = ProductVariantValue::where('id', $item['variant_value_id'])
                    ->whereHas('variant', function($q) use ($item) {
                        $q->where('product_id', $item['product_id']);
                    })
                    ->first();

                if (!$variantValue) {
                    $removedItems[] = $item['product_id'] . ' (variant)';
                    continue;
                }

                // Adjust quantity if exceeds stock
                if ($variantValue->stock < $item['quantity']) {
                    $item['quantity'] = $variantValue->stock;
                    if ($item['quantity'] <= 0) {
                        $removedItems[] = $item['product_id'] . ' (out of stock)';
                        continue;
                    }
                }
            } else {
                // Adjust quantity if exceeds stock
                if ($product->stock < $item['quantity']) {
                    $item['quantity'] = $product->stock;
                    if ($item['quantity'] <= 0) {
                        $removedItems[] = $item['product_id'] . ' (out of stock)';
                        continue;
                    }
                }
            }

            $validCartData[$sessionId] = $item;
        }

        session()->put('cart', $validCartData);

        return [
            'valid_items' => count($validCartData),
            'removed_items' => $removedItems
        ];
    }
}