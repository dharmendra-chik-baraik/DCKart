@extends('admin.layouts.app')

@section('title', 'Payment Details - Admin Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payment Details</h1>
        <a href="{{ route('admin.payments.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Payments
        </a>
    </div>

    <div class="row">
        <!-- Payment Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                    <span class="badge badge-{{ getPaymentStatusBadge($payment->payment_status) }} p-2">
                        {{ ucfirst($payment->payment_status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Transaction ID</th>
                                    <td>
                                        <code>{{ $payment->transaction_id ?? 'N/A' }}</code>
                                        @if($payment->transaction_id)
                                        <button class="btn btn-sm btn-outline-secondary ml-2" onclick="copyToClipboard('{{ $payment->transaction_id }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Order Number</th>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-primary">
                                            #{{ $payment->order->order_number ?? 'N/A' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Method</th>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($payment->payment_method) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td class="font-weight-bold text-success">₹{{ number_format($payment->amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Customer</th>
                                    <td>
                                        <strong>{{ $payment->user->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $payment->user->email ?? '' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Date</th>
                                    <td>{{ $payment->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $payment->updated_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Details</th>
                                    <td>
                                        <pre class="mb-0" style="max-height: 100px; overflow-y: auto;">{{ json_encode($payment->payment_details, JSON_PRETTY_PRINT) }}</pre>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Order Items -->
                    @if($payment->order && $payment->order->items)
                    <div class="mt-4">
                        <h6 class="font-weight-bold">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Vendor</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payment->order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ $item->vendor->shop_name ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>₹{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions & Status Update -->
        <div class="col-lg-4">
            <!-- Status Update -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Payment Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.update-status', $payment->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="payment_status">Payment Status</label>
                            <select class="form-control" id="payment_status" name="payment_status" required>
                                <option value="pending" {{ $payment->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $payment->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ $payment->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $payment->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="transaction_id">Transaction ID</label>
                            <input type="text" class="form-control" id="transaction_id" name="transaction_id" 
                                   value="{{ $payment->transaction_id }}" placeholder="Enter transaction ID if any">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    @if($payment->payment_status === 'completed')
                    <button type="button" class="btn btn-warning btn-block mb-2" data-toggle="modal" data-target="#refundModal">
                        <i class="fas fa-undo"></i> Process Refund
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-shopping-cart"></i> View Order Details
                    </a>
                    
                    <a href="mailto:{{ $payment->user->email ?? '' }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-envelope"></i> Contact Customer
                    </a>
                </div>
            </div>

            <!-- Payment Timeline -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold">Payment Created</h6>
                                <p class="text-muted mb-0">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($payment->payment_status === 'completed')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold">Payment Completed</h6>
                                <p class="text-muted mb-0">{{ $payment->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Refund</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.payments.refund', $payment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Amount to Refund</label>
                        <input type="number" class="form-control" name="refund_amount" 
                               value="{{ $payment->amount }}" step="0.01" min="0.01" max="{{ $payment->amount }}" required>
                    </div>
                    <div class="form-group">
                        <label>Refund Reason</label>
                        <textarea class="form-control" name="refund_reason" rows="3" placeholder="Enter reason for refund..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Process Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Transaction ID copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
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
}
</style>
@endpush