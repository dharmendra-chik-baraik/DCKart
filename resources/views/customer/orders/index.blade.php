@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 border">
            @include('customer.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">My Orders</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Stats -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-2">
                                    <div class="border-end">
                                        <h5 class="text-primary mb-1">{{ $orderStats['total'] ?? 0 }}</h5>
                                        <small class="text-muted">Total Orders</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="border-end">
                                        <h5 class="text-warning mb-1">{{ $orderStats['pending'] ?? 0 }}</h5>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="border-end">
                                        <h5 class="text-info mb-1">{{ $orderStats['confirmed'] ?? 0 }}</h5>
                                        <small class="text-muted">Confirmed</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="border-end">
                                        <h5 class="text-info mb-1">{{ $orderStats['processing'] ?? 0 }}</h5>
                                        <small class="text-muted">Processing</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="border-end">
                                        <h5 class="text-success mb-1">{{ $orderStats['delivered'] ?? 0 }}</h5>
                                        <small class="text-muted">Delivered</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <h5 class="text-danger mb-1">{{ $orderStats['cancelled'] ?? 0 }}</h5>
                                    <small class="text-muted">Cancelled</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0">Filter Orders</h6>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" class="d-flex gap-2">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Orders</option>
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @if($status)
                                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">Clear</a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="card">
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Vendor</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->order_number }}</strong>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($order->vendor)
                                                {{ $order->vendor->shop_name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->items->count() }} items</td>
                                        <td>₹{{ number_format($order->grand_total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_color }}">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('customer.orders.show', $order->id) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($order->canBeCancelled())
                                                    <form action="{{ route('customer.orders.cancel', $order->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-outline-danger" 
                                                                onclick="return confirm('Are you sure you want to cancel this order?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-4x text-muted mb-4"></i>
                            <h4>No orders found</h4>
                            <p class="text-muted mb-4">
                                @if($status)
                                    You don't have any {{ $status }} orders.
                                @else
                                    You haven't placed any orders yet.
                                @endif
                            </p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection