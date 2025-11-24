@extends('layouts.app')

@section('title', 'Products - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Categories -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Categories</h6>
                            @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" 
                                       value="{{ $category->slug }}" 
                                       {{ request('category') == $category->slug ? 'checked' : '' }}
                                       id="cat{{ $category->id }}">
                                <label class="form-check-label" for="cat{{ $category->id }}">
                                    {{ $category->name }} ({{ $category->products_count }})
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Price Range</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control form-control-sm" 
                                           placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control form-control-sm" 
                                           placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Sort -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Sort By</h6>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">Clear Filters</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold">All Products</h1>
                <div class="text-muted">
                    Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} products
                </div>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card product-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img src="{{ $product->images->first()->image_path ?? 'https://cdn.pixabay.com/photo/2016/11/29/08/41/apple-1868496_1280.jpg' }}" 
                                 class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-2">
                                @if($product->is_featured)
                                <span class="badge bg-danger">Featured</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ Str::limit($product->name, 50) }}</h6>
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
                                    View Details
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
                                    Out of Stock
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No products found</h4>
                        <p class="text-muted">Try adjusting your search filters</p>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection