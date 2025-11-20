<div class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse" id="adminNavbar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <!-- User Management -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            
            <!-- Vendor Management -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/vendors*') ? 'active' : '' }}" href="{{ route('admin.vendors.index') }}">
                    <i class="fas fa-store"></i> Vendors
                </a>
            </li>
            
            <!-- Categories -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-tags"></i> Categories
                </a>
            </li>
            
            <!-- Products -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            
            <!-- Orders -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" href="#">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
            </li>
            
            <!-- Reports -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}" href="#">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
            
            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="#">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
        </ul>
    </div>
</div>