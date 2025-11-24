@extends('layouts.app')

@section('title', 'Shopping Cart - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Shopping Cart</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-4">Shopping Cart</h1>
        </div>
    </div>

    @if(isset($cartItems) && $cartItems->count() > 0)
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Cart Items ({{ $cartItems->count() }})</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="cart-item border-bottom pb-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="{{ $item->product->images->first()->image_path ?? 'https://cdn.pixabay.com/photo/2016/11/29/08/41/apple-1868496_1280.jpg' }}" 
                                     alt="{{ $item->product->name }}" class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold mb-1">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none text-dark">
                                        {{ $item->product->name }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-1">Sold by: {{ $item->product->vendor->shop_name }}</p>
                                @if($item->variantValue)
                                <p class="text-muted small mb-0">
                                    <strong>{{ $item->variantValue->variant->name }}:</strong> 
                                    {{ $item->variantValue->value }}
                                    @if($item->variantValue->price_adjustment > 0)
                                    <span class="text-success">+₹{{ number_format($item->variantValue->price_adjustment, 2) }}</span>
                                    @endif
                                </p>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <span class="h6 text-primary fw-bold">
                                    ₹{{ number_format($item->variantValue ? ($item->product->price + $item->variantValue->price_adjustment) : $item->product->price, 2) }}
                                </span>
                            </div>
                            <div class="col-md-2">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                           min="1" max="10" class="form-control form-control-sm" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2" title="Update Quantity">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Item">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to clear your cart?')">
                                <i class="fas fa-trash me-2"></i>Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal ({{ $cartItems->count() }} items):</span>
                        <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (18%):</span>
                        <span>₹{{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="text-success">FREE</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total:</strong>
                        <strong class="text-primary h5">₹{{ number_format($total, 2) }}</strong>
                    </div>

                    @auth
                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="fas fa-lock me-2"></i>Proceed to Checkout
                    </a>
                    @else
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Checkout
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                            Create Account
                        </a>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Security Badges -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body text-center">
                    <h6 class="fw-bold mb-3">Secure Shopping</h6>
                    <div class="row g-2">
                        <div class="col-4">
                            <i class="fas fa-shield-alt text-success fa-2x mb-2"></i>
                            <p class="small mb-0">100% Secure</p>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-truck text-info fa-2x mb-2"></i>
                            <p class="small mb-0">Free Delivery</p>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-undo text-warning fa-2x mb-2"></i>
                            <p class="small mb-0">Easy Returns</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">Your cart is empty</h3>
                    <p class="text-muted mb-4">Add some amazing products to your cart and start shopping</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection