@extends('admin.layouts.app')

@section('title', 'Payout Details - Admin Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payout Details</h1>
        <a href="{{ route('admin.payouts.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Payouts
        </a>
    </div>

    <div class="row">
        <!-- Payout Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Payout Information</h6>
                    <span class="badge badge-{{ getPayoutStatusBadge($payout->status) }} p-2">
                        {{ ucfirst($payout->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Payout ID</th>
                                    <td><strong>#{{ $payout->id }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td class="font-weight-bold text-success">₹{{ number_format($payout->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Transaction ID</th>
                                    <td>
                                        <code>{{ $payout->transaction_id ?? 'N/A' }}</code>
                                        @if($payout->transaction_id)
                                        <button class="btn btn-sm btn-outline-secondary ml-2" onclick="copyToClipboard('{{ $payout->transaction_id }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created Date</th>
                                    <td>{{ $payout->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Vendor</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($payout->vendor->logo)
                                            <img src="{{ asset('storage/' . $payout->vendor->logo) }}" 
                                                 alt="{{ $payout->vendor->shop_name }}" 
                                                 class="rounded-circle mr-2" width="40" height="40">
                                            @else
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-store"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $payout->vendor->shop_name }}</strong><br>
                                                <small class="text-muted">{{ $payout->vendor->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $payout->updated_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td>{{ $payout->remarks ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Vendor Earnings Summary -->
                    <div class="mt-4">
                        <h6 class="font-weight-bold">Vendor Earnings Summary</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Earnings</h6>
                                        <h4 class="mb-0">₹{{ number_format($vendorEarnings['total_earnings'], 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title">Paid Amount</h6>
                                        <h4 class="mb-0">₹{{ number_format($vendorEarnings['paid_amount'], 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title">Pending Payouts</h6>
                                        <h4 class="mb-0">₹{{ number_format($vendorEarnings['pending_payouts'], 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title">Available Balance</h6>
                                        <h4 class="mb-0">₹{{ number_format($vendorEarnings['balance'], 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions & Status Update -->
        <div class="col-lg-4">
            <!-- Status Update -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Payout Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payouts.update-status', $payout->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="status">Payout Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending" {{ $payout->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processed" {{ $payout->status == 'processed' ? 'selected' : '' }}>Processed</option>
                                <option value="failed" {{ $payout->status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="transaction_id">Transaction ID</label>
                            <input type="text" class="form-control" id="transaction_id" name="transaction_id" 
                                   value="{{ $payout->transaction_id }}" placeholder="Enter transaction ID">
                        </div>
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3" 
                                      placeholder="Enter any remarks...">{{ $payout->remarks }}</textarea>
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
                    <a href="mailto:{{ $payout->vendor->user->email }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-envelope"></i> Contact Vendor
                    </a>
                    
                    <a href="{{ route('admin.vendors.show', $payout->vendor_id) }}" class="btn btn-secondary btn-block mb-2">
                        <i class="fas fa-store"></i> View Vendor Profile
                    </a>

                    @if($payout->status === 'pending' && $vendorEarnings['balance'] > 0)
                    <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#createPayoutModal">
                        <i class="fas fa-plus"></i> Create New Payout
                    </button>
                    @endif
                </div>
            </div>

            <!-- Payout Timeline -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payout Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold">Payout Created</h6>
                                <p class="text-muted mb-0">{{ $payout->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($payout->status === 'processed')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold">Payout Processed</h6>
                                <p class="text-muted mb-0">{{ $payout->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @elseif($payout->status === 'failed')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold">Payout Failed</h6>
                                <p class="text-muted mb-0">{{ $payout->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create New Payout Modal -->
@if($payout->status === 'pending' && $vendorEarnings['balance'] > 0)
<div class="modal fade" id="createPayoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Payout</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.payouts.vendor.generate-payout', $payout->vendor_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Payout Amount</label>
                        <input type="number" class="form-control" name="amount" 
                               value="{{ min($vendorEarnings['balance'], 10000) }}" 
                               step="0.01" min="0.01" max="{{ $vendorEarnings['balance'] }}" required>
                        <small class="form-text text-muted">
                            Available Balance: ₹{{ number_format($vendorEarnings['balance'], 2) }}
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks" rows="3" 
                                  placeholder="Enter remarks for new payout..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create Payout</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Copied to clipboard!');
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