@extends('layouts.app')

@section('title', 'My Wishlist - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">My Wishlist</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-4">My Wishlist</h1>
        </div>
    </div>

    @if(isset($wishlistItems) && $wishlistItems->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-heart me-2"></i>
                You have {{ $wishlistItems->count() }} item(s) in your wishlist
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($wishlistItems as $wishlistItem)
        <div class="col-lg-3 col-md-6">
            <div class="card product-card border-0 shadow-sm h-100">
                <div class="position-relative">
                    <img src="{{ $wishlistItem->product->images->first()->image_path ?? 'https://cdn.pixabay.com/photo/2016/11/29/08/41/apple-1868496_1280.jpg' }}" 
                         class="card-img-top" alt="{{ $wishlistItem->product->name }}" style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-2">
                        <form action="{{ route('wishlist.remove', $wishlistItem->product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Remove from Wishlist">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                    @if($wishlistItem->product->is_featured)
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-danger">Featured</span>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <h6 class="card-title fw-bold">
                        <a href="{{ route('products.show', $wishlistItem->product->slug) }}" class="text-decoration-none text-dark">
                            {{ Str::limit($wishlistItem->product->name, 50) }}
                        </a>
                    </h6>
                    <p class="card-text text-muted small mb-2">{{ Str::limit($wishlistItem->product->short_description, 80) }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="h5 text-primary fw-bold">₹{{ number_format($wishlistItem->product->price, 2) }}</span>
                            @if($wishlistItem->product->sale_price)
                            <span class="text-muted text-decoration-line-through small ms-2">₹{{ number_format($wishlistItem->product->sale_price, 2) }}</span>
                            @endif
                        </div>
                        <span class="text-warning small">
                            <i class="fas fa-star"></i> 4.5
                        </span>
                    </div>
                    <small class="text-muted">Sold by: {{ $wishlistItem->product->vendor->shop_name }}</small>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', $wishlistItem->product->slug) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                        @if($wishlistItem->product->stock_status === 'in_stock')
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $wishlistItem->product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                            </button>
                        </form>
                        @else
                        <button class="btn btn-secondary btn-sm w-100" disabled>
                            <i class="fas fa-times me-1"></i> Out of Stock
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-plus me-2"></i>Add More Items
            </a>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-heart fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">Your wishlist is empty</h3>
                    <p class="text-muted mb-4">Save your favorite products here for easy access later</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Browse Products
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-2"></i>Go Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection