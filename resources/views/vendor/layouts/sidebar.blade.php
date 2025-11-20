<div class="col-md-3 col-lg-2 d-md-block vendor-sidebar collapse" id="vendorNavbar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <!-- Products Management -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/products*') ? 'active' : '' }}" href="{{ route('vendor.products.index') }}">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            
            <!-- Orders -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/orders*') ? 'active' : '' }}" href="{{ route('vendor.orders.index') }}">
                    <i class="fas fa-shopping-cart"></i> Orders
                    <span class="badge bg-warning rounded-pill float-end">{{ Auth::user()->vendor->pending_orders_count ?? 0 }}</span>
                </a>
            </li>
            
            <!-- Inventory -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/inventory*') ? 'active' : '' }}" href="{{ route('vendor.inventory.index') }}">
                    <i class="fas fa-warehouse"></i> Inventory
                </a>
            </li>
            
            <!-- Promotions -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/promotions*') ? 'active' : '' }}" href="{{ route('vendor.promotions.index') }}">
                    <i class="fas fa-percentage"></i> Promotions
                </a>
            </li>
            
            <!-- Reviews -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/reviews*') ? 'active' : '' }}" href="{{ route('vendor.reviews.index') }}">
                    <i class="fas fa-star"></i> Reviews
                </a>
            </li>
            
            <!-- Earnings -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/earnings*') ? 'active' : '' }}" href="{{ route('vendor.earnings.index') }}">
                    <i class="fas fa-chart-line"></i> Earnings
                </a>
            </li>
            
            <!-- Store Settings -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/store*') ? 'active' : '' }}" href="{{ route('vendor.store.edit') }}">
                    <i class="fas fa-store"></i> Store Settings
                </a>
            </li>
            
            <!-- Reports -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/reports*') ? 'active' : '' }}" href="{{ route('vendor.reports.index') }}">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
        </ul>
        
        <!-- Store Status -->
        <div class="mt-4 p-3 bg-dark bg-opacity-25 rounded">
            <h6 class="text-white mb-2">Store Status</h6>
            @php
                $storeStatus = Auth::user()->vendor->status ?? 'inactive';
                $statusColor = $storeStatus === 'active' ? 'success' : ($storeStatus === 'pending' ? 'warning' : 'danger');
            @endphp
            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($storeStatus) }}</span>
        </div>
    </div>
</div>