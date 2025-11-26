@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 border">
            @include('customer.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="card-title mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h4>
                                    <p class="card-text mb-0">Here's what's happening with your orders today.</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="bg-white bg-opacity-25 rounded p-3 d-inline-block">
                                        <i class="fas fa-shopping-bag fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Orders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Spent</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($totalSpent, 2) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Orders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['pending'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Delivered Orders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['delivered'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status Overview -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order Status Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-2 mb-3">
                                    <div class="border-end pe-3">
                                        <h5 class="text-primary mb-1">{{ $orderStats['total'] }}</h5>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="border-end pe-3">
                                        <h5 class="text-warning mb-1">{{ $orderStats['pending'] }}</h5>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="border-end pe-3">
                                        <h5 class="text-info mb-1">{{ $orderStats['confirmed'] }}</h5>
                                        <small class="text-muted">Confirmed</small>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="border-end pe-3">
                                        <h5 class="text-info mb-1">{{ $orderStats['processing'] }}</h5>
                                        <small class="text-muted">Processing</small>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="border-end pe-3">
                                        <h5 class="text-success mb-1">{{ $orderStats['delivered'] }}</h5>
                                        <small class="text-muted">Delivered</small>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <h5 class="text-danger mb-1">{{ $orderStats['cancelled'] }}</h5>
                                    <small class="text-muted">Cancelled</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Orders -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Orders</h5>
                            <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        </div>
                        <div class="card-body">
                            @if($recentOrders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Vendor</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentOrders as $order)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $order->order_number }}</strong>
                                                </td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    @if($order->vendor)
                                                        {{ Str::limit($order->vendor->shop_name, 20) }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>₹{{ number_format($order->grand_total, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $order->status_color }}">
                                                        {{ ucfirst($order->order_status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('customer.orders.show', $order->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5>No orders yet</h5>
                                    <p class="text-muted">You haven't placed any orders yet.</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary">
                                        <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Stats -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('home') }}" class="btn btn-outline-primary text-start">
                                    <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
                                </a>
                                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-info text-start">
                                    <i class="fas fa-list me-2"></i>View All Orders
                                </a>
                                <a href="{{ route('customer.profile.edit') }}" class="btn btn-outline-secondary text-start">
                                    <i class="fas fa-user me-2"></i>Update Profile
                                </a>
                                <a href="{{ route('customer.addresses.index') }}" class="btn btn-outline-warning text-start">
                                    <i class="fas fa-address-book me-2"></i>Manage Addresses
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status Distribution -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order Distribution</h5>
                        </div>
                        <div class="card-body">
                            @if($orderStats['total'] > 0)
                                <div class="mb-3">
                                    @php
                                        $statuses = [
                                            'pending' => ['count' => $orderStats['pending'], 'color' => 'warning'],
                                            'confirmed' => ['count' => $orderStats['confirmed'], 'color' => 'info'],
                                            'processing' => ['count' => $orderStats['processing'], 'color' => 'primary'],
                                            'delivered' => ['count' => $orderStats['delivered'], 'color' => 'success'],
                                            'cancelled' => ['count' => $orderStats['cancelled'], 'color' => 'danger']
                                        ];
                                    @endphp
                                    
                                    @foreach($statuses as $status => $data)
                                        @if($data['count'] > 0)
                                            @php
                                                $percentage = ($data['count'] / $orderStats['total']) * 100;
                                            @endphp
                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-capitalize">{{ $status }}</span>
                                                    <span>{{ $data['count'] }} ({{ number_format($percentage, 1) }}%)</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-{{ $data['color'] }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $percentage }}%"
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="text-center text-muted small">
                                    Total Orders: {{ $orderStats['total'] }}
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-chart-pie fa-2x mb-2"></i>
                                    <p>No order data available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="card-title">Need Help?</h5>
                            <p class="card-text">If you have any questions about your orders or need assistance, our support team is here to help.</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="fas fa-headset me-2"></i>Contact Support
                                </a>
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="fas fa-question-circle me-2"></i>Help Center
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection