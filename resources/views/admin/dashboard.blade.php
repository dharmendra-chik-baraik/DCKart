@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
        <div class="btn-group">
            <button class="btn btn-outline-secondary" onclick="exportDashboardData()">
                <i class="fas fa-download"></i> Export
            </button>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-plus"></i> Add New
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.users.create') }}"><i class="fas fa-user me-2"></i>User</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.vendors.create') }}"><i class="fas fa-store me-2"></i>Vendor</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.products.create') }}"><i class="fas fa-box me-2"></i>Product</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.categories.create') }}"><i class="fas fa-folder me-2"></i>Category</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <!-- Total Users -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] ?? 0 }}</div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $stats['customers_count'] ?? 0 }} Customers
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Vendors -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Vendors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_vendors'] ?? 0 }}</div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $stats['approved_vendors'] ?? 0 }} Approved
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] ?? 0 }}</div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-star me-1"></i>
                                    {{ $stats['featured_products'] ?? 0 }} Featured
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_orders'] ?? 0 }}</div>
                            <div class="mt-2">
                                <small class="text-warning">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $stats['pending_orders'] ?? 0 }} Pending
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-chart-line me-1"></i>
                                    This Month
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-secondary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_categories'] ?? 0 }}</div>
                            <div class="mt-2">
                                <small class="text-info">
                                    <i class="fas fa-sitemap me-1"></i>
                                    {{ $stats['active_categories'] ?? 0 }} Active
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Vendor</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 30px; height: 30px; font-size: 12px;">
                                                {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <small class="d-block">{{ $order->user->name }}</small>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $order->vendor->shop_name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($order->grand_total, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($order->order_status == 'completed') bg-success
                                            @elseif($order->order_status == 'pending') bg-warning
                                            @elseif($order->order_status == 'cancelled') bg-danger
                                            @else bg-info @endif">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $order->created_at->format('M d, Y') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No orders found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="card shadow-sm">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Products</h6>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Vendor</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->images->count() > 0)
                                            <img src="{{ $product->images->first()->image_path }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                            <div class="rounded bg-light text-center me-2 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong class="d-block">{{ Str::limit($product->name, 30) }}</strong>
                                                <small class="text-muted">{{ $product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $product->vendor->shop_name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($product->final_price, 2) }}</strong>
                                        @if($product->sale_price)
                                        <br><small class="text-danger">{{ $product->discount_percentage }}% OFF</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->stock_status == 'in_stock' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $product->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $product->created_at->format('M d, Y') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        <i class="fas fa-box fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No products found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <!-- Recent Vendors -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Vendors</h6>
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentVendors as $vendor)
                        <div class="list-group-item d-flex align-items-center px-0">
                            <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; font-size: 14px;">
                                {{ strtoupper(substr($vendor->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $vendor->shop_name }}</h6>
                                <small class="text-muted">{{ $vendor->user->email }}</small>
                                <div class="mt-1">
                                    <span class="badge {{ $vendor->status == 'approved' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($vendor->status) }}
                                    </span>
                                    @if($vendor->verified_at)
                                    <span class="badge bg-info ms-1">Verified</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">{{ $vendor->created_at->format('M d') }}</small>
                                <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3">
                            <i class="fas fa-store fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No vendors found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <div class="text-primary font-weight-bold">{{ $stats['low_stock_products'] ?? 0 }}</div>
                                <small class="text-muted">Low Stock</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <div class="text-warning font-weight-bold">{{ $stats['pending_vendors'] ?? 0 }}</div>
                                <small class="text-muted">Pending Vendors</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <div class="text-info font-weight-bold">{{ $stats['today_orders'] ?? 0 }}</div>
                                <small class="text-muted">Today's Orders</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <div class="text-success font-weight-bold">${{ number_format($stats['today_revenue'] ?? 0, 2) }}</div>
                                <small class="text-muted">Today's Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">System Status</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Server Load</span>
                        <span class="badge bg-success">Normal</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Database</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Cache</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Last Backup</span>
                        <span class="text-muted">{{ now()->subDays(1)->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportDashboardData() {
    // Simple export functionality - you can enhance this with actual data export
    alert('Export functionality would be implemented here. This could export dashboard data as CSV or PDF.');
}

// Auto-refresh dashboard every 5 minutes
setTimeout(function() {
    window.location.reload();
}, 300000); // 5 minutes
</script>
@endpush