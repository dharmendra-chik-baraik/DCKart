<div class="card">
    <div class="card-body p-0">
        <!-- User Profile Section -->
        <div class="text-center p-4 border-bottom">
            <div class="position-relative d-inline-block">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                    style="width: 80px; height: 80px;">
                    <i class="fas fa-user text-white fa-2x"></i>
                </div>
                @if (auth()->user()->status === 'active')
                    <span class="position-absolute bottom-0 end-0 bg-success border border-3 border-white rounded-circle"
                        style="width: 15px; height: 15px;" title="Active"></span>
                @endif
            </div>
            <h5 class="mt-3 mb-1 fw-bold">{{ auth()->user()->name }}</h5>
            <p class="text-muted small mb-2">{{ auth()->user()->email }}</p>
            <span class="badge bg-primary">Customer</span>
        </div>

        <!-- Navigation Menu -->
        <nav class="nav flex-column p-3">
            <!-- Dashboard -->
            <a class="nav-link sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
                href="{{ route('customer.dashboard') }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    <span>Dashboard</span>
                </div>
            </a>

            <!-- Orders -->
            <a class="nav-link sidebar-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}"
                href="{{ route('customer.orders.index') }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shopping-bag me-3"></i>
                    <span>My Orders</span>
                </div>
                <span class="badge bg-primary ms-auto">5</span>
            </a>

            <!-- Wishlist -->
            <a class="nav-link sidebar-link {{ request()->routeIs('customer.wishlist*') ? 'active' : '' }}"
                href="{{ route('customer.wishlist.index') }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-heart me-3"></i>
                    <span>Wishlist</span>
                </div>
                <span class="badge bg-danger ms-auto">3</span>
            </a>

            <!-- Addresses -->
            <a class="nav-link sidebar-link {{ request()->routeIs('customer.addresses*') ? 'active' : '' }}"
                href="{{ route('customer.addresses.index') }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-map-marker-alt me-3"></i>
                    <span>My Addresses</span>
                </div>
            </a>

            <!-- Profile -->
            <a class="nav-link sidebar-link {{ request()->routeIs('customer.profile*') ? 'active' : '' }}"
                href="{{ route('customer.profile.index') }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user me-3"></i>
                    <span>Profile</span>
                </div>
            </a>
            <!-- Support -->
            <a class="nav-link sidebar-link {{ request()->routeIs('customer.support*') ? 'active' : '' }}"
                href="#">
                <div class="d-flex align-items-center">
                    <i class="fas fa-headset me-3"></i>
                    <span>Support</span>
                </div>
            </a>

            <!-- Divider -->
            <hr class="my-2">

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit"
                    class="nav-link sidebar-link text-danger w-100 text-start border-0 bg-transparent">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sign-out-alt me-3"></i>
                        <span>Logout</span>
                    </div>
                </button>
            </form>
        </nav>
    </div>
</div>

<!-- Quick Stats Card -->
<div class="card mt-3">
    <div class="card-header bg-transparent">
        <h6 class="mb-0">Quick Stats</h6>
    </div>
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small">Total Orders:</span>
            <span class="fw-bold text-primary">12</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small">Pending Orders:</span>
            <span class="fw-bold text-warning">2</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small">Wishlist Items:</span>
            <span class="fw-bold text-danger">5</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <span class="small">Total Spent:</span>
            <span class="fw-bold text-success">₹15,250</span>
        </div>
    </div>
</div>

<!-- Support Card -->
<div class="card mt-3">
    <div class="card-body text-center p-3">
        <i class="fas fa-headset fa-2x text-primary mb-2"></i>
        <h6 class="mb-2">Need Help?</h6>
        <p class="small text-muted mb-2">We're here to help you</p>
        <a href="#" class="btn btn-sm btn-outline-primary w-100">Contact Support</a>
    </div>
</div>

<style>
    .sidebar-link {
        border: none;
        padding: 12px 15px;
        margin: 2px 0;
        border-radius: 8px;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sidebar-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
        transform: translateX(5px);
    }

    .sidebar-link.active {
        background-color: #0d6efd;
        color: white;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
    }

    .sidebar-link .badge {
        font-size: 0.7rem;
    }

    .sidebar-link i {
        width: 20px;
        text-align: center;
    }
</style>
