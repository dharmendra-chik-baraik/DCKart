<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\WishlistService;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index()
    {
        try {
            $wishlistItems = $this->wishlistService->getWishlistItems();
            return view('frontend.wishlist.index', compact('wishlistItems'));

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }

    public function add(Product $product)
    {
        try {
            $this->wishlistService->addToWishlist($product->id);
            return redirect()->back()->with('success', 'Product added to wishlist.');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }

    public function remove(Product $product)
    {
        try {
            $this->wishlistService->removeFromWishlist($product->id);
            return redirect()->back()->with('success', 'Product removed from wishlist.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}