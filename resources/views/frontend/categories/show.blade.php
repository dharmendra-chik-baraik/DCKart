@extends('layouts.app')

@section('title', $category->name . ' - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Category Header -->
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body text-center p-5">
                    @if($category->image)
                    <div class="category-image mb-4">
                        <img src="{{ $category->image }}" alt="{{ $category->name }}" 
                             class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    @endif
                    
                    <div class="category-icon mb-3">
                        @if($category->icon)
                            <i class="{{ $category->icon }} fa-3x text-primary"></i>
                        @else
                            <i class="fas fa-tag fa-3x text-primary"></i>
                        @endif
                    </div>
                    
                    <h1 class="fw-bold text-primary mb-3">{{ $category->name }}</h1>
                    
                    @if($category->description)
                    <p class="lead text-muted mb-4">{{ $category->description }}</p>
                    @endif
                    
                    <div class="category-stats">
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <div class="stat-item text-center">
                                    <div class="h3 fw-bold text-primary mb-1">{{ $products->total() }}</div>
                                    <small class="text-muted">Products</small>
                                </div>
                            </div>
                            @if($category->children->count() > 0)
                            <div class="col-auto">
                                <div class="stat-item text-center">
                                    <div class="h3 fw-bold text-success mb-1">{{ $category->children->count() }}</div>
                                    <small class="text-muted">Subcategories</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar - Subcategories & Filters -->
        <div class="col-lg-3 mb-4">
            <!-- Subcategories -->
            @if($category->children->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Subcategories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($category->children as $subcategory)
                        <a href="{{ route('categories.show', $subcategory->slug) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                @if($subcategory->icon)
                                    <i class="{{ $subcategory->icon }} me-2 text-primary"></i>
                                @endif
                                {{ $subcategory->name }}
                            </span>
                            <span class="badge bg-primary rounded-pill">{{ $subcategory->products_count ?? 0 }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Filters -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <!-- Price Range -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Price Range</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" id="minPrice" class="form-control form-control-sm" 
                                       placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" id="maxPrice" class="form-control form-control-sm" 
                                       placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Availability -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Availability</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inStock" checked>
                            <label class="form-check-label" for="inStock">
                                In Stock
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="outOfStock">
                            <label class="form-check-label" for="outOfStock">
                                Out of Stock
                            </label>
                        </div>
                    </div>

                    <!-- Vendor -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Vendors</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="verifiedVendors" checked>
                            <label class="form-check-label" for="verifiedVendors">
                                Verified Vendors Only
                            </label>
                        </div>
                    </div>

                    <button type="button" id="applyFilters" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                    <button type="button" id="clearFilters" class="btn btn-outline-secondary btn-sm w-100 mt-2">Clear Filters</button>
                </div>
            </div>

            <!-- Category Info -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">About This Category</h6>
                    <p class="small text-muted mb-0">
                        Explore our wide range of {{ strtolower($category->name) }} products from trusted vendors. 
                        Find the best deals and quality products in this category.
                    </p>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Products Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 fw-bold mb-1">Products in {{ $category->name }}</h2>
                    <p class="text-muted mb-0">Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} products</p>
                </div>
                
                <!-- Sort Options -->
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">Sort by:</span>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" 
                                {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" 
                                {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" 
                                {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}" 
                                {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" 
                                {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>
            </div>

            @if($products->count() > 0)
            <!-- Products Grid -->
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-xl-4 col-lg-6">
                    <div class="card product-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img src="{{ $product->images->first()->image_path ?? 'https://cdn.pixabay.com/photo/2016/11/29/08/41/apple-1868496_1280.jpg' }}" 
                                 class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-2">
                                @if($product->is_featured)
                                <span class="badge bg-danger">Featured</span>
                                @endif
                                @if($product->sale_price)
                                <span class="badge bg-success">Sale</span>
                                @endif
                            </div>
                            @if($product->stock_status !== 'in_stock')
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <span class="badge bg-warning text-dark">Out of Stock</span>
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
                            
                            <div class="vendor-info mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-store me-1"></i>
                                    <a href="{{ route('vendors.show', $product->vendor->shop_slug) }}" class="text-decoration-none">
                                        {{ $product->vendor->shop_name }}
                                    </a>
                                    @if($product->vendor->verified_at)
                                    <i class="fas fa-check-circle text-success ms-1" title="Verified Vendor"></i>
                                    @endif
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="h5 text-primary fw-bold">₹{{ number_format($product->price, 2) }}</span>
                                    @if($product->sale_price)
                                    <span class="text-muted text-decoration-line-through small ms-2">₹{{ number_format($product->sale_price, 2) }}</span>
                                    @endif
                                </div>
                                <span class="text-warning small">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <small class="text-muted">(4.5)</small>
                                </span>
                            </div>

                            @if($product->stock_status === 'in_stock')
                            <div class="stock-info">
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    In Stock ({{ $product->stock }} available)
                                </small>
                            </div>
                            @else
                            <div class="stock-info">
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Out of Stock
                                </small>
                            </div>
                            @endif
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
            <!-- No Products Found -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">No products found</h3>
                    <p class="text-muted mb-4">There are currently no products available in this category.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Browse All Products
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-tags me-2"></i>View Other Categories
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Related Categories -->
    @if($relatedCategories && $relatedCategories->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">Related Categories</h3>
                    <div class="row g-3">
                        @foreach($relatedCategories as $relatedCategory)
                        <div class="col-md-3 col-6">
                            <a href="{{ route('categories.show', $relatedCategory->slug) }}" 
                               class="card category-card text-decoration-none text-dark border-0 shadow-sm h-100">
                                <div class="card-body text-center p-3">
                                    <div class="category-icon mb-2">
                                        @if($relatedCategory->icon)
                                            <i class="{{ $relatedCategory->icon }} fa-2x text-primary"></i>
                                        @else
                                            <i class="fas fa-tag fa-2x text-primary"></i>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $relatedCategory->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $relatedCategory->products_count }} products</p>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.category-card, .product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-card:hover, .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.stat-item {
    padding: 0 1.5rem;
}

.list-group-item {
    border: none;
    padding: 0.75rem 0;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.stock-info {
    margin-top: 0.5rem;
}

.vendor-info a:hover {
    text-decoration: underline !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .category-stats .row {
        gap: 2rem;
    }
    
    .stat-item {
        padding: 0 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const applyFilters = document.getElementById('applyFilters');
    const clearFilters = document.getElementById('clearFilters');
    
    applyFilters.addEventListener('click', function() {
        const minPrice = document.getElementById('minPrice').value;
        const maxPrice = document.getElementById('maxPrice').value;
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        
        if (minPrice) params.set('min_price', minPrice);
        if (maxPrice) params.set('max_price', maxPrice);
        
        // Remove empty parameters
        params.forEach((value, key) => {
            if (!value) params.delete(key);
        });
        
        window.location.href = url.pathname + '?' + params.toString();
    });
    
    clearFilters.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });
    
    // Enter key support for price filters
    ['minPrice', 'maxPrice'].forEach(id => {
        document.getElementById(id).addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters.click();
            }
        });
    });
});
</script>
@endsection