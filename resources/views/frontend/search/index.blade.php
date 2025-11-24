@extends('layouts.app')

@section('title', 'Search Results for "' . $query . '" - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Search Results</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2">Search Results</h1>
            <div class="d-flex align-items-center">
                <p class="text-muted mb-0 me-3">Showing results for: </p>
                <span class="badge bg-primary fs-6">"{{ $query }}"</span>
            </div>
        </div>
    </div>

    @if(isset($products) && $products->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center">
                <i class="fas fa-info-circle fa-lg me-3"></i>
                <div>
                    <strong>Found {{ $products->total() }} product(s)</strong> matching your search criteria
                </div>
            </div>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0">Refine your search:</h6>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm active">All</button>
                                <button type="button" class="btn btn-outline-primary btn-sm">In Stock</button>
                                <button type="button" class="btn btn-outline-primary btn-sm">On Sale</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card product-card border-0 shadow-sm h-100">
                <div class="position-relative">
                    <img src="{{ $product->images->first()->image_path ?? 'https://cdn.pixabay.com/photo/2016/11/29/08/41/apple-1868496_1280.jpg' }}" 
                         class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @if($product->is_featured)
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-danger">Featured</span>
                    </div>
                    @endif
                    @if($product->sale_price)
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-success">Sale</span>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <h6 class="card-title fw-bold">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                            {{ Str::limit($product->name, 50) }}
                        </a>
                    </h6>
                    <p class="card-text text-muted small mb-2">{{ Str::limit($product->short_description, 80) }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="h5 text-primary fw-bold">₹{{ number_format($product->price, 2) }}</span>
                            @if($product->sale_price)
                            <span class="text-muted text-decoration-line-through small ms-2">₹{{ number_format($product->sale_price, 2) }}</span>
                            @endif
                        </div>
                        <span class="text-warning small">
                            <i class="fas fa-star"></i> 4.5
                        </span>
                    </div>
                    <small class="text-muted">Sold by: {{ $product->vendor->shop_name }}</small>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                        @if($product->stock_status === 'in_stock')
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
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

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">No products found</h3>
                    <p class="text-muted mb-4">We couldn't find any products matching "<strong>{{ $query }}</strong>"</p>
                    
                    <div class="d-flex justify-content-center gap-3 mb-5">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Browse All Products
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Go Home
                        </a>
                    </div>
                    
                    <!-- Search Tips -->
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Search Tips:</h5>
                                    <div class="row text-start">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Check your spelling
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Try more general keywords
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Use fewer keywords
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Try different keywords
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Browse by category
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Check our popular products
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Popular Categories -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h5 class="fw-bold mb-4">Popular Categories</h5>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="{{ route('categories.show', 'electronics') }}" class="btn btn-outline-primary">Electronics</a>
                                <a href="{{ route('categories.show', 'fashion') }}" class="btn btn-outline-primary">Fashion</a>
                                <a href="{{ route('categories.show', 'home-garden') }}" class="btn btn-outline-primary">Home & Garden</a>
                                <a href="{{ route('categories.show', 'beauty') }}" class="btn btn-outline-primary">Beauty</a>
                                <a href="{{ route('categories.show', 'sports') }}" class="btn btn-outline-primary">Sports</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.btn-group .btn.active {
    background-color: #6366f1;
    border-color: #6366f1;
    color: white;
}
</style>
@endsection