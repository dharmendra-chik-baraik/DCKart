@extends('admin.layouts.app')

@section('title', 'Create Payout - Admin Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Payout</h1>
        <a href="{{ route('admin.payouts.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Payouts
        </a>
    </div>

    <div class="row">
        <!-- Create Payout Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payout Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payouts.store') }}" method="POST" id="payoutForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="vendor_id">Select Vendor *</label>
                            <select class="form-control select2" id="vendor_id" name="vendor_id" required>
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    @php
                                        // Find vendor earnings from the pre-loaded data
                                        $vendorEarnings = collect($vendorsWithEarnings)
                                            ->firstWhere('vendor.id', $vendor->id);
                                        $balance = $vendorEarnings ? $vendorEarnings['earnings']['balance'] : 0;
                                    @endphp
                                    <option value="{{ $vendor->id }}" data-balance="{{ $balance }}">
                                        {{ $vendor->shop_name }} ({{ $vendor->user->email }}) - Balance: ₹{{ number_format($balance, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vendor_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">Payout Amount (₹) *</label>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   step="0.01" min="0.01" required placeholder="Enter payout amount">
                            <small class="form-text text-muted">
                                Available Balance: <span id="availableBalance">₹0.00</span>
                            </small>
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3" 
                                      placeholder="Enter any remarks for this payout..."></textarea>
                            @error('remarks')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Payout Information</h6>
                                <ul class="mb-0">
                                    <li>Payouts will be created with <strong>Pending</strong> status</li>
                                    <li>You can process the payout later from the payouts list</li>
                                    <li>Ensure the vendor has sufficient balance before creating payout</li>
                                </ul>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Payout</button>
                        <a href="{{ route('admin.payouts.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Vendors with Earnings -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vendors with Pending Earnings</h6>
                </div>
                <div class="card-body">
                    @if(count($vendorsWithEarnings) > 0)
                    <div class="list-group">
                        @foreach($vendorsWithEarnings as $vendorData)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $vendorData['vendor']->shop_name }}</h6>
                                <strong class="text-success">₹{{ number_format($vendorData['earnings']['balance'], 2) }}</strong>
                            </div>
                            <p class="mb-1 small text-muted">{{ $vendorData['vendor']->user->email }}</p>
                            <small>
                                Total Earnings: ₹{{ number_format($vendorData['earnings']['total_earnings'], 2) }} | 
                                Orders: {{ $vendorData['earnings']['total_orders'] }}
                            </small>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="selectVendor('{{ $vendorData['vendor']->id }}', {{ $vendorData['earnings']['balance'] }})">
                                    Select
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center">No vendors with pending earnings found.</p>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Vendors:</span>
                            <strong>{{ $vendors->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Vendors with Earnings:</span>
                            <strong>{{ count($vendorsWithEarnings) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Pending Amount:</span>
                            <strong class="text-warning">
                                ₹{{ number_format(collect($vendorsWithEarnings)->sum('earnings.balance'), 2) }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Vendor",
            allowClear: true
        });
    });

    // Update available balance when vendor is selected
    document.getElementById('vendor_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const balance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;
            document.getElementById('availableBalance').textContent = '₹' + balance.toFixed(2);
            document.getElementById('amount').setAttribute('max', balance);
        } else {
            document.getElementById('availableBalance').textContent = '₹0.00';
            document.getElementById('amount').removeAttribute('max');
        }
    });

    // Select vendor from the list
    function selectVendor(vendorId, balance) {
        document.getElementById('vendor_id').value = vendorId;
        document.getElementById('vendor_id').dispatchEvent(new Event('change'));
        document.getElementById('amount').focus();
        
        // Trigger Select2 update
        $('#vendor_id').trigger('change');
    }

    // Form validation
    document.getElementById('payoutForm').addEventListener('submit', function(e) {
        const amount = parseFloat(document.getElementById('amount').value);
        const maxAmount = parseFloat(document.getElementById('amount').getAttribute('max')) || 0;
        
        if (amount > maxAmount) {
            e.preventDefault();
            alert('Payout amount cannot exceed available balance of ₹' + maxAmount.toFixed(2));
            return false;
        }

        if (amount <= 0) {
            e.preventDefault();
            alert('Payout amount must be greater than 0');
            return false;
        }
    });
</script>
@endpush