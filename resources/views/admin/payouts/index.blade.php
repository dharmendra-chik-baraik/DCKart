@extends('admin.layouts.app')

@section('title', 'Vendor Payouts - Admin Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Vendor Payouts</h1>
        <a href="{{ route('admin.payouts.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create Payout
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Payouts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Processed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['processed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Failed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['failed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($stats['total_amount'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($stats['pending_amount'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payouts.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Payout Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ $filters['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processed" {{ $filters['status'] == 'processed' ? 'selected' : '' }}>Processed</option>
                                <option value="failed" {{ $filters['status'] == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="vendor_id">Vendor</label>
                            <select class="form-control" id="vendor_id" name="vendor_id">
                                <option value="">All Vendors</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ $filters['vendor_id'] == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->shop_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_from">Date From</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $filters['date_from'] }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_to">Date To</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $filters['date_to'] }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="Transaction ID, Remarks..." value="{{ $filters['search'] }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('admin.payouts.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payouts Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Payouts</h6>
            <div class="bulk-actions" style="display: none;">
                <select class="form-control form-control-sm d-inline-block w-auto" id="bulkAction">
                    <option value="">Bulk Actions</option>
                    <option value="processed">Mark as Processed</option>
                    <option value="failed">Mark as Failed</option>
                </select>
                <button class="btn btn-sm btn-primary" onclick="applyBulkAction()">Apply</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Payout ID</th>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Transaction ID</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payouts as $payout)
                        <tr>
                            <td>
                                <input type="checkbox" class="payout-checkbox" value="{{ $payout->id }}">
                            </td>
                            <td>
                                <strong>#{{ $payout->id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($payout->vendor->logo)
                                    <img src="{{ asset('storage/' . $payout->vendor->logo) }}" alt="{{ $payout->vendor->shop_name }}" 
                                         class="rounded-circle mr-2" width="30" height="30">
                                    @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" 
                                         style="width: 30px; height: 30px;">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $payout->vendor->shop_name }}</strong><br>
                                        <small class="text-muted">{{ $payout->vendor->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="font-weight-bold text-success">₹{{ number_format($payout->amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ getPayoutStatusBadge($payout->status) }} p-2">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </td>
                            <td>
                                <code>{{ $payout->transaction_id ?? 'N/A' }}</code>
                            </td>
                            <td>{{ $payout->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.payouts.show', $payout->id) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($payout->status === 'pending')
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#processModal{{ $payout->id }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif
                                </div>

                                <!-- Process Payout Modal -->
                                @if($payout->status === 'pending')
                                <div class="modal fade" id="processModal{{ $payout->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Process Payout</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.payouts.update-status', $payout->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Payout Status</label>
                                                        <select class="form-control" name="status" required>
                                                            <option value="processed">Mark as Processed</option>
                                                            <option value="failed">Mark as Failed</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Transaction ID</label>
                                                        <input type="text" class="form-control" name="transaction_id" 
                                                               placeholder="Enter transaction ID from payment gateway">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Remarks</label>
                                                        <textarea class="form-control" name="remarks" rows="3" 
                                                                  placeholder="Any additional remarks..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Update Status</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $payouts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Bulk selection functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.payout-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });

    document.querySelectorAll('.payout-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });

    function toggleBulkActions() {
        const checkedCount = document.querySelectorAll('.payout-checkbox:checked').length;
        const bulkActions = document.querySelector('.bulk-actions');
        if (checkedCount > 0) {
            bulkActions.style.display = 'block';
        } else {
            bulkActions.style.display = 'none';
        }
    }

    function applyBulkAction() {
        const action = document.getElementById('bulkAction').value;
        const checkedPayouts = Array.from(document.querySelectorAll('.payout-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (!action) {
            alert('Please select an action');
            return;
        }

        if (confirm(`Are you sure you want to mark ${checkedPayouts.length} payout(s) as ${action}?`)) {
            const transactionId = prompt('Enter transaction ID (optional):') || '';

            fetch('{{ route("admin.payouts.bulk-process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payout_ids: checkedPayouts,
                    status: action,
                    transaction_id: transactionId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        }
    }
</script>
@endpush