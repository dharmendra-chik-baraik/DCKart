@extends('admin.layouts.app')

@section('title', 'Orders Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Orders Management</h1>
            <div class="btn-group">
                <a href="{{ route('admin.orders.statistics') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar"></i> View Statistics
                </a>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders->total() }}</div>
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
                                    Total Revenue</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($orders->sum('grand_total'), 2) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders->where('order_status', 'pending')->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Cancelled Orders</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders->where('order_status', 'cancelled')->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card shadow-sm-sm mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Order #, Customer...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Order Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-control">
                            <option value="">All Payments</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card shadow-sm-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Orders List</h6>
                <span class="badge bg-primary">Total: {{ $orders->total() }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Vendor</th>
                                <th>Amount</th>
                                <th>Order Status</th>
                                <th>Payment Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="font-weight-bold text-primary">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; font-size: 14px;">
                                                {{ strtoupper(substr($order->user->name ?? 'N/A', 0, 2)) }}
                                            </div>
                                            <div class="ms-3">
                                                <strong>{{ $order->user->name ?? 'N/A' }}</strong>
                                                <br><small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $order->vendor->business_name ?? 'N/A' }}</td>
                                    <td>${{ number_format($order->grand_total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ getOrderStatusBadge($order->order_status) }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ getPaymentStatusBadge($order->payment_status) }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-info"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <!-- Actions Dropdown -->
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="dropdown-item">
                                                            <i class="fas fa-eye me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-warning update-status-btn"
                                                                data-order-id="{{ $order->id }}"
                                                                data-current-status="{{ $order->order_status }}">
                                                            <i class="fas fa-edit me-2"></i>Update Status
                                                        </button>
                                                    </li>
                                                    @if($order->canBeCancelled())
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger cancel-order-btn"
                                                                data-order-id="{{ $order->id }}">
                                                            <i class="fas fa-times me-2"></i>Cancel Order
                                                        </button>
                                                    </li>
                                                    @endif
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.orders.destroy', $order->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this order?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No orders found</h5>
                                        <p class="text-muted">Try adjusting your filters.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($orders->hasPages())
                    <div class="d-flex custom-pagination justify-content-between align-items-center mt-4">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStatusForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="orderId">
                        <div class="mb-3">
                            <label for="status" class="form-label">New Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Note (Optional)</label>
                            <textarea class="form-control" id="note" name="note" rows="3" 
                                      placeholder="Add a note about this status change..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cancelOrderForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="cancelOrderId">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Are you sure you want to cancel this order? This action cannot be undone.
                        </div>
                        <div class="mb-3">
                            <label for="cancel_note" class="form-label">Cancellation Reason (Optional)</label>
                            <textarea class="form-control" id="cancel_note" name="note" rows="3" 
                                      placeholder="Reason for cancellation..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Cancel Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th,
        .table td {
            vertical-align: middle;
        }
        .custom-pagination nav{
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-confirmed { background-color: #17a2b8; color: #fff; }
        .badge-processing { background-color: #007bff; color: #fff; }
        .badge-shipped { background-color: #6f42c1; color: #fff; }
        .badge-delivered { background-color: #28a745; color: #fff; }
        .badge-cancelled { background-color: #dc3545; color: #fff; }
        .badge-payment-pending { background-color: #ffc107; color: #000; }
        .badge-payment-completed { background-color: #28a745; color: #fff; }
        .badge-payment-failed { background-color: #dc3545; color: #fff; }
        .badge-payment-refunded { background-color: #6c757d; color: #fff; }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update Status Modal
            const updateStatusButtons = document.querySelectorAll('.update-status-btn');
            const updateStatusModal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
            const updateStatusForm = document.getElementById('updateStatusForm');

            base_url = '{{ url('/') }}';
            console.log(base_url);

            updateStatusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const currentStatus = this.getAttribute('data-current-status');

                    document.getElementById('orderId').value = orderId;
                    document.getElementById('status').value = currentStatus;

                    updateStatusForm.action = base_url + `/admin/orders/${orderId}/status`;
                    updateStatusModal.show();
                });
            });

            // Cancel Order Modal
            const cancelOrderButtons = document.querySelectorAll('.cancel-order-btn');
            const cancelOrderModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            const cancelOrderForm = document.getElementById('cancelOrderForm');

            cancelOrderButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');

                    document.getElementById('cancelOrderId').value = orderId;
                    cancelOrderForm.action = base_url + `/admin/orders/${orderId}/cancel`;
                    cancelOrderModal.show();
                });
            });

            // Form Submissions
            updateStatusForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this);
            });

            cancelOrderForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this);
            });

            function submitForm(form) {
                const formData = new FormData(form);

                // Add _method field for PUT requests
                formData.append('_method', 'PUT');

                // Clear any existing alerts before new submission
                const modalBody = document.querySelector('.modal .modal-body');
                if (modalBody) {
                    const existingAlerts = modalBody.querySelectorAll('.alert');
                    existingAlerts.forEach(alert => alert.remove());
                }

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(errorData.message || 'Request failed');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                        } else {
                            showAlert('error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('error', error.message || 'An error occurred. Please try again.');
                    });
            }

            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';

                const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

                const modalBody = document.querySelector('.modal .modal-body');
                if (modalBody) {
                    // Remove any existing alerts first
                    const existingAlerts = modalBody.querySelectorAll('.alert');
                    existingAlerts.forEach(alert => alert.remove());

                    // Insert new alert at the top of modal body
                    modalBody.insertAdjacentHTML('afterbegin', alertHtml);

                    // Auto-remove success alerts after 3 seconds
                    if (type === 'success') {
                        setTimeout(() => {
                            const alert = modalBody.querySelector('.alert');
                            if (alert) {
                                alert.remove();
                            }
                            // Close modal and reload page after success
                            const modal = bootstrap.Modal.getInstance(document.querySelector('.modal'));
                            if (modal) {
                                modal.hide();
                            }
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }, 2000);
                    }
                } else {
                    document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
                }
            }
        });
    </script>
@endpush