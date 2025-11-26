<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Customer\CartService;

class MergeGuestCart
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if user just logged in and has guest cart items
        if (auth()->check() && session()->has('cart')) {
            $this->cartService->mergeGuestCartWithUser(auth()->id());
        }

        return $response;
    }
}