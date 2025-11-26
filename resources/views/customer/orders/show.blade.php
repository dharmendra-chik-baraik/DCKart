@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 border">
            @include('customer.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Order #{{ $order->order_number }}</h4>
                    <p class="text-muted mb-0">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Orders
                    </a>
                    @if($order->canBeCancelled())
                    <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            <i class="fas fa-times me-2"></i>Cancel Order
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Order Status Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="steps">
                                @php
                                    $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                                    $currentStatus = $order->order_status;
                                @endphp
                                
                                @foreach($statuses as $status)
                                    @php
                                        $isCompleted = array_search($status, $statuses) <= array_search($currentStatus, $statuses);
                                        $isCurrent = $status === $currentStatus;
                                    @endphp
                                    <div class="step {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                        <div class="step-icon">
                                            @if($isCompleted)
                                                <i class="fas fa-check"></i>
                                            @else
                                                <span>{{ $loop->iteration }}</span>
                                            @endif
                                        </div>
                                        <div class="step-label">
                                            <strong>{{ ucfirst($status) }}</strong>
                                            @if($isCurrent)
                                                <small class="text-muted d-block">Current Status</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Status Badge -->
                    <div class="text-center mt-3">
                        <span class="badge bg-{{ $order->status_color }} fs-6 px-3 py-2">
                            Current Status: {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Order Items -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Items ({{ $order->items->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @foreach($order->items as $item)
                            <div class="order-item border-bottom pb-3 mb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        @if($item->product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                <i class="fas fa-image fa-2x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                        <p class="text-muted mb-1 small">
                                            @if($item->variantValue)
                                                Variant: {{ $item->variantValue->value }}
                                            @endif
                                        </p>
                                        <p class="text-muted mb-0 small">SKU: {{ $item->product->sku }}</p>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <p class="mb-1">Qty: {{ $item->quantity }}</p>
                                        <p class="text-muted small">₹{{ number_format($item->price, 2) }} each</p>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <strong>₹{{ number_format($item->quantity * $item->price, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Status Logs -->
                    @if($order->statusLogs->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order History</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @foreach($order->statusLogs->sortByDesc('created_at') as $log)
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <strong class="text-capitalize">{{ $log->status }}</strong>
                                            <small class="text-muted">{{ $log->created_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                        @if($log->note)
                                            <p class="mb-0 text-muted small">{{ $log->note }}</p>
                                        @endif
                                        <small class="text-muted">Changed by: {{ $log->changed_by }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <!-- Shipping Address -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $shippingAddress = $order->shippingAddress ?? $order->defaultShippingAddress;
                            @endphp
                            
                            @if($shippingAddress)
                            <address class="mb-0">
                                <strong>{{ $shippingAddress->name }}</strong><br>
                                {{ $shippingAddress->address_line_1 }}<br>
                                @if($shippingAddress->address_line_2)
                                    {{ $shippingAddress->address_line_2 }}<br>
                                @endif
                                {{ $shippingAddress->city }}, {{ $shippingAddress->state }}<br>
                                {{ $shippingAddress->pincode }}<br>
                                {{ $shippingAddress->country }}<br>
                                <i class="fas fa-phone me-1"></i> {{ $shippingAddress->phone }}
                            </address>
                            @else
                                <p class="text-muted mb-0">No shipping address found</p>
                            @endif
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₹{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>₹{{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span class="text-success">-₹{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between mb-0">
                                <strong>Grand Total:</strong>
                                <strong>₹{{ number_format($order->grand_total, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Payment Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Payment Method:</span>
                                <span>{{ ucfirst($order->payment_method) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Payment Status:</span>
                                <span class="badge bg-{{ $order->payment_status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            @if($order->transaction_id)
                            <div class="d-flex justify-content-between mb-0">
                                <span>Transaction ID:</span>
                                <span class="small">{{ $order->transaction_id }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Vendor Information -->
                    @if($order->vendor)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Vendor Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                @if($order->vendor->logo)
                                    <img src="{{ asset('storage/' . $order->vendor->logo) }}" 
                                         alt="{{ $order->vendor->shop_name }}" 
                                         class="rounded me-3" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-store text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong class="d-block">{{ $order->vendor->shop_name }}</strong>
                                    <small class="text-muted">Seller</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 2rem 0;
}

.steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    border: 3px solid white;
}

.step.completed .step-icon {
    background: #28a745;
    color: white;
}

.step.current .step-icon {
    background: #007bff;
    color: white;
}

.step-label {
    text-align: center;
    font-size: 0.875rem;
}

.timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    margin-bottom: 1rem;
}

.timeline-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #007bff;
    margin-right: 1rem;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.timeline-content {
    flex: 1;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.timeline-item:last-child .timeline-content {
    border-bottom: none;
    padding-bottom: 0;
}

.order-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
</style>
@endpush