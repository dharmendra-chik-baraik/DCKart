@extends('admin.layouts.app')

@section('title', 'Order Details - #' . $order->order_number)

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Order Details</h1>
                <p class="text-muted mb-0">Order #{{ $order->order_number }}</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
                <button type="button" class="btn btn-warning update-status-btn"
                        data-order-id="{{ $order->id }}"
                        data-current-status="{{ $order->order_status }}">
                    <i class="fas fa-edit"></i> Update Status
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Order Summary -->
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Variant</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if($item->product->images->first())
                                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    <br><small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->variantValue)
                                                <span class="badge bg-secondary">{{ $item->variantValue->value }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                        <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                    </tr>
                                    @if($order->discount > 0)
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                        <td><strong>-${{ number_format($order->discount, 2) }}</strong></td>
                                    </tr>
                                    @endif
                                    @if($order->shipping_charge > 0)
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Shipping:</strong></td>
                                        <td><strong>${{ number_format($order->shipping_charge, 2) }}</strong></td>
                                    </tr>
                                    @endif
                                    @if($order->tax > 0)
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                        <td><strong>${{ number_format($order->tax, 2) }}</strong></td>
                                    </tr>
                                    @endif
                                    <tr class="table-active">
                                        <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                                        <td><strong>${{ number_format($order->grand_total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status History -->
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Status History</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach($order->statusLogs->sortByDesc('created_at') as $log)
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker badge-{{ getOrderStatusBadge($log->status) }}"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <strong class="text-capitalize">{{ $log->status }}</strong>
                                        <small class="text-muted">{{ $log->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    @if($log->note)
                                    <p class="mb-0 text-muted">{{ $log->note }}</p>
                                    @endif
                                    @if($log->changed_by)
                                    <small class="text-muted">Changed by: {{ $log->changed_by }}</small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="col-lg-4">
                <!-- Order Status Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="badge badge-{{ getOrderStatusBadge($order->order_status) }} fs-6 p-2">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                        <div class="text-center mb-3">
                            <span class="badge badge-{{ getPaymentStatusBadge($order->payment_status) }} fs-6 p-2">
                                Payment: {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        @if($order->canBeCancelled())
                        <button type="button" class="btn btn-danger btn-sm w-100 cancel-order-btn"
                                data-order-id="{{ $order->id }}">
                            <i class="fas fa-times"></i> Cancel Order
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; font-size: 16px;">
                                {{ strtoupper(substr($order->user->name, 0, 2)) }}
                            </div>
                            <div class="ms-3">
                                <strong>{{ $order->user->name }}</strong>
                                <br><small class="text-muted">{{ $order->user->email }}</small>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <strong>Contact Info:</strong><br>
                            <small class="text-muted">
                                Phone: {{ $order->user->phone ?? 'N/A' }}<br>
                                Member since: {{ $order->user->created_at->format('M Y') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Order Date:</strong><br>
                            <span class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Shipping Method:</strong><br>
                            <span class="text-muted">{{ $order->shipping_method ?? 'Standard' }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Payment Method:</strong><br>
                            <span class="text-muted">{{ $order->payment_method ?? 'N/A' }}</span>
                        </div>
                        @if($order->transaction_id)
                        <div class="mb-2">
                            <strong>Transaction ID:</strong><br>
                            <span class="text-muted">{{ $order->transaction_id }}</span>
                        </div>
                        @endif
                        <div class="mb-2">
                            <strong>Vendor:</strong><br>
                            <span class="text-muted">{{ $order->vendor->business_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the same modals from index.blade.php -->
    @include('admin.orders.partials.modals')
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .timeline-content {
        padding-bottom: 10px;
        border-left: 2px solid #e3e6f0;
        padding-left: 15px;
    }
    .timeline-item:last-child .timeline-content {
        border-left: 2px solid transparent;
    }
</style>
@endpush

@push('scripts')
<script>
    // Same JavaScript as index.blade.php for modal handling
    document.addEventListener('DOMContentLoaded', function() {
        // Update Status Modal
        const updateStatusButton = document.querySelector('.update-status-btn');
        const updateStatusModal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
        const updateStatusForm = document.getElementById('updateStatusForm');
        
        if (updateStatusButton) {
            updateStatusButton.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const currentStatus = this.getAttribute('data-current-status');
                
                document.getElementById('orderId').value = orderId;
                document.getElementById('status').value = currentStatus;
                
                updateStatusForm.action = `/admin/orders/${orderId}/status`;
                updateStatusModal.show();
            });
        }

        // Cancel Order Modal
        const cancelOrderButton = document.querySelector('.cancel-order-btn');
        const cancelOrderModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
        const cancelOrderForm = document.getElementById('cancelOrderForm');
        
        if (cancelOrderButton) {
            cancelOrderButton.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                
                document.getElementById('cancelOrderId').value = orderId;
                cancelOrderForm.action = `/admin/orders/${orderId}/cancel`;
                cancelOrderModal.show();
            });
        }

        // Form submissions remain the same...
    });
</script>
@endpush