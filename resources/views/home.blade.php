@extends('layouts.app')

@section('title', 'Home - ' . config('app.name', 'MultiVendor'))

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Discover Amazing Products from Local Vendors</h1>
                <p class="lead mb-4">Shop from thousands of unique products crafted by independent sellers. Find everything you need in one marketplace.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#products" class="btn btn-light btn-lg px-4 py-2 fw-semibold">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </a>
                    <a href="#vendors" class="btn btn-outline-light btn-lg px-4 py-2">
                        <i class="fas fa-store me-2"></i>Become a Vendor
                    </a>
                </div>
                <div class="mt-4 d-flex flex-wrap gap-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-warning me-2"></i>
                        <span>100% Secure Payments</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shipping-fast text-warning me-2"></i>
                        <span>Fast Delivery</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-headset text-warning me-2"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://cdn.pixabay.com/photo/2017/08/06/22/01/online-2600284_1280.jpg" 
                     alt="Online Shopping" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold text-dark mb-3">Why Choose Our Platform?</h2>
                <p class="text-muted lead">We provide the best shopping experience for both buyers and sellers</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="feature-icon bg-primary rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-shield-alt fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Secure Shopping</h5>
                        <p class="text-muted">Your transactions are protected with industry-leading security measures and encryption.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="feature-icon bg-success rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-rocket fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Fast Delivery</h5>
                        <p class="text-muted">Quick shipping options with real-time tracking for all your orders.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="feature-icon bg-warning rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-handshake fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Trusted Vendors</h5>
                        <p class="text-muted">All our vendors are verified and rated by our community of shoppers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5" id="categories">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-dark mb-3">Shop by Category</h2>
                <p class="text-muted lead">Browse through our wide range of product categories</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <a href="#" class="card category-card text-decoration-none text-dark border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="category-icon mb-3">
                            <i class="fas fa-tshirt fa-2x text-primary"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Fashion</h6>
                        <p class="text-muted small mb-0">Clothing & Accessories</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#" class="card category-card text-decoration-none text-dark border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="category-icon mb-3">
                            <i class="fas fa-laptop fa-2x text-info"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Electronics</h6>
                        <p class="text-muted small mb-0">Gadgets & Devices</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#" class="card category-card text-decoration-none text-dark border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="category-icon mb-3">
                            <i class="fas fa-home fa-2x text-success"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Home & Garden</h6>
                        <p class="text-muted small mb-0">Furniture & Decor</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#" class="card category-card text-decoration-none text-dark border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="category-icon mb-3">
                            <i class="fas fa-heartbeat fa-2x text-danger"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Health & Beauty</h6>
                        <p class="text-muted small mb-0">Cosmetics & Wellness</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light" id="products">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-dark mb-3">Featured Products</h2>
                <p class="text-muted lead">Handpicked items from our top vendors</p>
            </div>
        </div>
        <div class="row g-4">
            @for($i = 1; $i <= 8; $i++)
            <div class="col-lg-3 col-md-6">
                <div class="card product-card border-0 shadow-sm h-100">
                    <div class="position-relative">
                        <img src="https://cdn.pixabay.com/photo/2016/11/29/08/41/apple-1868496_1280.jpg" 
                             class="card-img-top" alt="Product {{ $i }}" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-danger">Hot</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold">Premium Product {{ $i }}</h6>
                        <p class="card-text text-muted small">High-quality product with excellent features</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h5 text-primary fw-bold">${{ rand(20, 200) }}</span>
                                <span class="text-muted text-decoration-line-through small ms-2">${{ rand(250, 400) }}</span>
                            </div>
                            <span class="text-warning small">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="#" class="btn btn-outline-primary btn-lg px-5">View All Products</a>
            </div>
        </div>
    </div>
</section>

<!-- Vendor Section -->
<section class="py-5" id="vendors">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold text-dark mb-4">Become a Vendor Today</h2>
                <p class="text-muted mb-4">Join thousands of sellers who are growing their business on our platform. Reach millions of customers and grow your brand.</p>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Easy Setup:</strong> Get started in minutes with our simple onboarding process
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Powerful Tools:</strong> Manage inventory, orders, and customers from one dashboard
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Secure Payments:</strong> Get paid securely with our reliable payment system
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Marketing Support:</strong> Reach more customers with our marketing tools
                    </li>
                </ul>
                <div class="mt-4">
                    <a href="#" class="btn btn-success btn-lg px-4 me-3">
                        <i class="fas fa-store me-2"></i>Start Selling
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-lg px-4">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://cdn.pixabay.com/photo/2018/03/10/12/00/paper-3213924_1280.jpg" 
                     alt="Vendor Benefits" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <h3 class="display-4 fw-bold text-primary">10K+</h3>
                    <p class="mb-0">Happy Customers</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <h3 class="display-4 fw-bold text-success">500+</h3>
                    <p class="mb-0">Verified Vendors</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <h3 class="display-4 fw-bold text-warning">50K+</h3>
                    <p class="mb-0">Products Available</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <h3 class="display-4 fw-bold text-info">100+</h3>
                    <p class="mb-0">Cities Served</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-3">Stay Updated</h3>
                <p class="mb-4">Subscribe to our newsletter for the latest products and exclusive deals</p>
                <form class="d-flex gap-2">
                    <input type="email" class="form-control form-control-lg" placeholder="Enter your email">
                    <button type="submit" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-paper-plane me-2"></i>Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.min-vh-75 {
    min-height: 75vh;
}

.feature-icon {
    transition: transform 0.3s ease;
}

.card:hover .feature-icon {
    transform: scale(1.1);
}

.category-card, .product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-card:hover, .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.stat-item {
    padding: 20px 0;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .hero-section {
        text-align: center;
        padding: 3rem 0;
    }
}
</style>

@endsection