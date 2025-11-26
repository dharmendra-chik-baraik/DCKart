<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        try {
            // Validate cart items on page load
            $this->cartService->validateGuestCart();
            
            $data = $this->cartService->getCartData();
            return view('frontend.cart.index', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
            'variant_value_id' => 'nullable|exists:product_variant_values,id'
        ]);

        try {
            $result = $this->cartService->addToCart(
                $request->product_id,
                $request->quantity,
                $request->variant_value_id
            );

            $message = 'Product added to cart successfully.';
            if (auth()->check()) {
                $message .= ' (' . $this->cartService->getCartCount() . ' items in cart)';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        try {
            $this->cartService->updateCartItem($id, $request->quantity);
            
            $message = 'Cart updated successfully.';
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            $this->cartService->removeFromCart($id);
            return redirect()->back()->with('success', 'Product removed from cart.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function clear()
    {
        try {
            $this->cartService->clearCart();
            return redirect()->back()->with('success', 'Cart cleared successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getCartCount()
    {
        $count = $this->cartService->getCartCount();
        return response()->json(['count' => $count]);
    }
}