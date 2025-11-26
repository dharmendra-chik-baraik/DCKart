<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Interfaces\Customer\WishlistRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        private WishlistRepositoryInterface $wishlistRepository,
        private ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Display customer wishlist
     */
    public function index(): View
    {
        $userId = auth()->id();
        $wishlistItems = $this->wishlistRepository->getUserWishlist($userId);
        
        return view('customer.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add product to wishlist
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = auth()->id();
        $productId = $request->product_id;

        // Check if product exists and is active
        $product = $this->productRepository->getProductById($productId);
        if (!$product || $product->status != 1) {
            return redirect()->back()->with('error', 'Product not available.');
        }

        // Check if already in wishlist
        if ($this->wishlistRepository->isInWishlist($userId, $productId)) {
            return redirect()->back()->with('info', 'Product is already in your wishlist.');
        }

        $added = $this->wishlistRepository->addToWishlist($userId, $productId);
        
        if ($added) {
            return redirect()->back()->with('success', 'Product added to wishlist successfully.');
        }

        return redirect()->back()->with('error', 'Failed to add product to wishlist.');
    }

    /**
     * Remove product from wishlist
     */
    public function destroy(string $productId): RedirectResponse
    {
        $userId = auth()->id();
        
        $removed = $this->wishlistRepository->removeFromWishlist($userId, $productId);
        
        if ($removed) {
            return redirect()->back()->with('success', 'Product removed from wishlist.');
        }

        return redirect()->back()->with('error', 'Failed to remove product from wishlist.');
    }

    /**
     * Clear entire wishlist
     */
    public function clear(): RedirectResponse
    {
        $userId = auth()->id();
        
        $cleared = $this->wishlistRepository->clearWishlist($userId);
        
        if ($cleared) {
            return redirect()->back()->with('success', 'Wishlist cleared successfully.');
        }

        return redirect()->back()->with('error', 'Failed to clear wishlist.');
    }

    /**
     * Move wishlist item to cart
     */
    public function moveToCart(string $productId): RedirectResponse
    {
        $userId = auth()->id();
        
        $moved = $this->wishlistRepository->moveToCart($userId, $productId);
        
        if ($moved) {
            return redirect()->route('cart.index')->with('success', 'Product moved to cart successfully.');
        }

        return redirect()->back()->with('error', 'Failed to move product to cart.');
    }
}