@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 border">
            @include('customer.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">My Wishlist</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($wishlistItems->count() > 0)
                <!-- Wishlist Header -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-0">
                                    <i class="fas fa-heart text-danger me-2"></i>
                                    {{ $wishlistItems->count() }} 
                                    {{ Str::plural('item', $wishlistItems->count()) }} in wishlist
                                </h6>
                            </div>
                            <div class="col-md-6 text-end">
                                <form action="{{ route('customer.wishlist.clear') }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to clear your entire wishlist?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash me-2"></i>Clear All
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wishlist Items -->
                <div class="row">
                    @foreach($wishlistItems as $item)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 product-card">
                            <!-- Product Image -->
                            <div class="position-relative">
                                @if($item->product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                         class="card-img-top" 
                                         alt="{{ $item->product->name }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif

                                <!-- Wishlist Remove Button -->
                                <div class="position-absolute top-0 end-0 p-2">
                                    <form action="{{ route('customer.wishlist.destroy', $item->id) }}" 
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-light rounded-circle"
                                                onclick="return confirm('Remove from wishlist?')">
                                            <i class="fas fa-times text-danger"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Sale Badge -->
                                @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-danger">Sale</span>
                                    </div>
                                @endif

                                <!-- Stock Status -->
                                <div class="position-absolute bottom-0 start-0 m-2">
                                    @if($item->product->stock_status === 'out_of_stock')
                                        <span class="badge bg-secondary">Out of Stock</span>
                                    @elseif($item->product->stock_status === 'backorder')
                                        <span class="badge bg-warning">Backorder</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Vendor -->
                                @if($item->product->vendor)
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-store me-1"></i>
                                        {{ $item->product->vendor->shop_name }}
                                    </small>
                                @endif

                                <!-- Product Name -->
                                <h6 class="card-title">
                                    <a href="{{ route('products.show', $item->product->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($item->product->name, 50) }}
                                    </a>
                                </h6>

                                <!-- Product Price -->
                                <div class="mb-2">
                                    @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                        <span class="h6 text-danger mb-0">₹{{ number_format($item->product->sale_price, 2) }}</span>
                                        <small class="text-muted text-decoration-line-through ms-1">
                                            ₹{{ number_format($item->product->price, 2) }}
                                        </small>
                                        @php
                                            $discount = (($item->product->price - $item->product->sale_price) / $item->product->price) * 100;
                                        @endphp
                                        <small class="text-success ms-1">({{ number_format($discount, 0) }}% off)</small>
                                    @else
                                        <span class="h6 text-dark mb-0">₹{{ number_format($item->product->price, 2) }}</span>
                                    @endif
                                </div>

                                <!-- Product Rating -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-warning small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <small class="text-muted ms-1">(4.5)</small>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    @if($item->product->stock_status === 'in_stock')
                                        <form action="{{ route('customer.wishlist.move-to-cart', $item->product->id) }}" 
                                              method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-shopping-cart me-2"></i>Move to Cart
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-outline-secondary w-100" disabled>
                                            <i class="fas fa-clock me-2"></i>
                                            @if($item->product->stock_status === 'out_of_stock')
                                                Out of Stock
                                            @else
                                                On Backorder
                                            @endif
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('products.show', $item->product->slug) }}" 
                                       class="btn btn-outline-info">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Empty State (if all items are removed via AJAX) -->
                <div id="empty-wishlist" class="d-none">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-heart fa-4x text-muted mb-4"></i>
                            <h4>Your wishlist is empty</h4>
                            <p class="text-muted mb-4">Start adding products you love to your wishlist.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                            </a>
                        </div>
                    </div>
                </div>

            @else
                <!-- Empty Wishlist State -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-heart fa-4x text-muted mb-4"></i>
                        <h4>Your wishlist is empty</h4>
                        <p class="text-muted mb-4">Start adding products you love to your wishlist.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card-img-top {
    transition: transform 0.3s ease;
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}
</style>
@endpush

@push('scripts')
<script>
// Add smooth animations for wishlist actions
document.addEventListener('DOMContentLoaded', function() {
    // Remove item animation
    const removeButtons = document.querySelectorAll('form[action*="wishlist"] button[type="submit"]');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.closest('form').action.includes('destroy')) {
                const card = this.closest('.col-lg-4, .col-md-6');
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(100px)';
                    
                    setTimeout(() => {
                        card.remove();
                        
                        // Check if wishlist is empty
                        const remainingItems = document.querySelectorAll('.col-lg-4, .col-md-6');
                        if (remainingItems.length === 0) {
                            document.getElementById('empty-wishlist').classList.remove('d-none');
                        }
                    }, 300);
                }
            }
        });
    });

    // Move to cart animation
    const moveToCartForms = document.querySelectorAll('form[action*="move-to-cart"]');
    
    moveToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Moving...';
            button.disabled = true;
        });
    });
});
</script>
@endpush