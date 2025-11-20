<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
            <i class="fas fa-store me-2"></i>
            {{ config('app.name', 'MultiVendor') }}
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Left Navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Categories
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-tshirt me-2"></i>Fashion</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-laptop me-2"></i>Electronics</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-home me-2"></i>Home & Garden</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-heartbeat me-2"></i>Health & Beauty</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-th-large me-2"></i>All Categories</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#vendors">Vendors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Deals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
            </ul>

            <!-- Search Bar -->
            <form class="d-flex me-3" role="search">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Search products..." aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- Right Navigation -->
            <ul class="navbar-nav">
                @auth
                    <!-- User is logged in -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="#"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
                            @elseif(Auth::user()->role === 'vendor')
                                <li><a class="dropdown-item" href="#"><i class="fas fa-store me-2"></i>Vendor Dashboard</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-box me-2"></i>My Products</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-shopping-cart me-2"></i>Orders</a></li>
                            @else
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-shopping-bag me-2"></i>My Orders</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-heart me-2"></i>Wishlist</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="#">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Cart -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="#">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                    </li>

                    <!-- Notifications -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                5
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2" style="width: 300px;">
                            <li class="dropdown-header">Notifications</li>
                            <li><a class="dropdown-item" href="#">New order received</a></li>
                            <li><a class="dropdown-item" href="#">Product review added</a></li>
                            <li><a class="dropdown-item" href="#">New message</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">View All</a></li>
                        </ul>
                    </li>
                @else
                    <!-- User is not logged in -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-shopping-cart"></i> Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="{{ route('home') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar-brand {
    font-size: 1.5rem;
}

.navbar-nav .nav-link {
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: color 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: #667eea !important;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: background-color 0.3s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.input-group .form-control:focus {
    border-color: #667eea;
    box-shadow: none;
}

.badge {
    font-size: 0.6rem;
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 1020;
}

@media (max-width: 991.98px) {
    .navbar-nav .nav-item {
        margin: 0.2rem 0;
    }
    
    .input-group {
        margin: 1rem 0;
    }
}
</style>