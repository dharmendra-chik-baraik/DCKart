@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <img src="{{ $product->images->first()->image_path ?? 'https://cdn.pixabay.com/photo/2016/11/29/08/41/apple-1868496_1280.jpg' }}" 
                     class="card-img-top" alt="{{ $product->name }}" style="max-height: 500px; object-fit: cover;">
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="h2 fw-bold mb-3">{{ $product->name }}</h1>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning me-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-muted">({{ $product->reviews->count() }} reviews)</span>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-primary fw-bold">₹{{ number_format($product->price, 2) }}</h2>
                        @if($product->sale_price)
                        <span class="text-muted text-decoration-line-through">₹{{ number_format($product->sale_price, 2) }}</span>
                        <span class="badge bg-danger ms-2">Save {{ number_format((($product->sale_price - $product->price) / $product->sale_price) * 100, 0) }}%</span>
                        @endif
                    </div>

                    <p class="text-muted mb-4">{{ $product->description }}</p>

                    <div class="mb-4">
                        <strong>Vendor:</strong> 
                        <a href="{{ route('vendors.show', $product->vendor->shop_slug) }}" class="text-decoration-none">
                            {{ $product->vendor->shop_name }}
                        </a>
                    </div>

                    <div class="mb-4">
                        <strong>Category:</strong> 
                        <a href="{{ route('categories.show', $product->category->slug) }}" class="text-decoration-none">
                            {{ $product->category->name }}
                        </a>
                    </div>

                    @if($product->stock_status === 'in_stock')
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i>In Stock ({{ $product->stock }} available)
                    </div>
                    @else
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>Out of Stock
                    </div>
                    @endif

                    <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-auto">
                                <label for="quantity" class="col-form-label">Quantity:</label>
                            </div>
                            <div class="col-auto">
                                <input type="number" name="quantity" id="quantity" 
                                       class="form-control" value="1" min="1" max="{{ $product->stock }}" style="width: 80px;">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary btn-lg flex-fill" 
                                    {{ $product->stock_status === 'in_stock' ? '' : 'disabled' }}>
                                <i class="fas fa-shopping-cart me-2"></i>
                                Add to Cart
                            </button>
                            <form action="{{ route('customer.wishlist.add', $product) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-lg w-100">
                                    <i class="fas fa-heart me-2"></i>
                                    Wishlist
                                </button>
                            </form>
                        </div>
                    </form>

                    <div class="d-flex gap-3 text-muted small">
                        <div>
                            <i class="fas fa-shipping-fast me-1"></i>
                            Free Shipping
                        </div>
                        <div>
                            <i class="fas fa-undo me-1"></i>
                            30-Day Returns
                        </div>
                        <div>
                            <i class="fas fa-shield-alt me-1"></i>
                            Secure Payment
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description & Reviews -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                                Description
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                                Reviews ({{ $product->reviews->count() }})
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content p-3" id="productTabsContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            {!! $product->description !!}
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            @forelse($product->reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>{{ $review->user->name }}</strong>
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                        @endfor
                                    </span>
                                </div>
                                <p class="mb-2">{{ $review->review }}</p>
                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                            </div>
                            @empty
                            <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Related Products</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img src="{{ $relatedProduct->images->first()->image_path ?? 'https://cdn.pixabay.com/photo/2016/11/29/08/41/apple-1868496_1280.jpg' }}" 
                                 class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ Str::limit($relatedProduct->name, 50) }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary fw-bold">₹{{ number_format($relatedProduct->price, 2) }}</span>
                                <span class="text-warning small">
                                    <i class="fas fa-star"></i> 4.5
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection