<div class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse" id="adminNavbar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <!-- User Management -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i> Users
                    <span class="badge bg-primary ms-1">{{ $totalUsers }}</span>
                </a>
            </li>
            
            <!-- Vendor Management -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/vendors*') ? 'active' : '' }}" href="{{ route('admin.vendors.index') }}">
                    <i class="fas fa-store"></i> Vendors
                    @if($pendingVendorsCount > 0)
                    <span class="badge bg-warning ms-1">{{ $pendingVendorsCount }}</span>
                    @endif
                </a>
            </li>
            
            <!-- Categories -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-tags"></i> Categories
                    <span class="badge bg-info ms-1">{{ $totalCategories }}</span>
                </a>
            </li>
            
            <!-- Products -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box"></i> Products
                    <span class="badge bg-info ms-1">{{ $totalProducts }}</span>
                </a>
            </li>
            
            <!-- Orders -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-cart"></i> Orders
                    @if($pendingOrdersCount > 0)
                    <span class="badge bg-warning ms-1">{{ $pendingOrdersCount }}</span>
                    @endif
                </a>
            </li>
            
            <!-- Inventory Management -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/inventory*') ? 'active' : '' }}" href="{{ route('admin.inventory.index') }}">
                    <i class="fas fa-warehouse"></i> Inventory
                    @if($lowStockCount > 0)
                    <span class="badge bg-danger ms-1">{{ $lowStockCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Low Stock Alert -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/inventory/low-stock*') ? 'active' : '' }}" href="{{ route('admin.inventory.low-stock') }}">
                    <i class="fas fa-exclamation-triangle"></i> Low Stock
                    @if($lowStockCount > 0)
                    <span class="badge bg-danger ms-1">{{ $lowStockCount }}</span>
                    @endif
                </a>
            </li>
            
            <!-- Coupons & Discounts -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/coupons*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                    <i class="fas fa-ticket-alt"></i> Coupons
                    <span class="badge bg-success ms-1">{{ $totalCoupons }}</span>
                </a>
            </li>

            <!-- Product Reviews -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/reviews*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                    <i class="fas fa-star"></i> Reviews
                    @if($pendingReviewsCount > 0)
                    <span class="badge bg-warning ms-1">{{ $pendingReviewsCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Sales Reports -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/reports/sales*') ? 'active' : '' }}" href="{{ route('admin.reports.sales') }}">
                    <i class="fas fa-chart-line"></i> Sales Reports
                </a>
            </li>

            <!-- Product Analytics -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/reports/products*') ? 'active' : '' }}" href="{{ route('admin.reports.products') }}">
                    <i class="fas fa-chart-bar"></i> Product Analytics
                </a>
            </li>

            <!-- Vendor Performance -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/reports/vendors*') ? 'active' : '' }}" href="{{ route('admin.reports.vendors') }}">
                    <i class="fas fa-chart-pie"></i> Vendor Performance
                </a>
            </li>

            <!-- Payments -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/payments*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                    <i class="fas fa-credit-card"></i> Payments
                    @if($pendingPaymentsCount > 0)
                    <span class="badge bg-warning ms-1">{{ $pendingPaymentsCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Vendor Payouts -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/payouts*') ? 'active' : '' }}" href="{{ route('admin.payouts.index') }}">
                    <i class="fas fa-money-bill-wave"></i> Vendor Payouts
                    @if($pendingPayoutsCount > 0)
                    <span class="badge bg-warning ms-1">{{ $pendingPayoutsCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Tickets/Support -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/tickets*') ? 'active' : '' }}" href="{{ route('admin.tickets.index') }}">
                    <i class="fas fa-headset"></i> Support Tickets
                    @if($openTicketsCount > 0)
                    <span class="badge bg-danger ms-1">{{ $openTicketsCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Pages -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/pages*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                    <i class="fas fa-file"></i> Pages
                    <span class="badge bg-secondary ms-1">{{ $totalPages }}</span>
                </a>
            </li>

            <!-- Activity Logs -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/activity-logs*') ? 'active' : '' }}" href="{{ route('admin.activity-logs.index') }}">
                    <i class="fas fa-history"></i> Activity Logs
                </a>
            </li>

            <!-- System Settings -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cog"></i> System Settings
                </a>
            </li>

            <!-- Shipping Methods -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/shipping*') ? 'active' : '' }}" href="#admin.shipping.index') }}">
                    <i class="fas fa-shipping-fast"></i> Shipping Methods
                    <span class="badge bg-secondary ms-1">{{ $totalShippingMethods }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>