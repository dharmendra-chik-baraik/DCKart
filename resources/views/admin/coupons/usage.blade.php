@extends('admin.layouts.app')

@section('title', 'Coupon Usage - ' . $coupon->code)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center mb-4">
                    <h1 class="page-title h3 mb-0 text-gray-800">Coupon Usage: <code>{{ $coupon->code }}</code></h1>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                            <li class="breadcrumb-item active">Coupon Usage</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coupon Summary -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h6>Coupon Details</h6>
                                <p class="mb-1"><strong>Code:</strong> <code>{{ $coupon->code }}</code></p>
                                <p class="mb-1"><strong>Discount:</strong>
                                    @if ($coupon->discount_type === 'percentage')
                                        {{ $coupon->discount_value }}%
                                        @if ($coupon->max_discount)
                                            (Max: ₹{{ number_format($coupon->max_discount, 2) }})
                                        @endif
                                    @else
                                        ₹{{ number_format($coupon->discount_value, 2) }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <h6>Usage Limits</h6>
                                <p class="mb-1"><strong>Used:</strong> {{ $coupon->used_count }} times</p>
                                <p class="mb-1"><strong>Limit:</strong>
                                    {{ $coupon->usage_limit ? $coupon->usage_limit . ' uses' : 'Unlimited' }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <h6>Validity</h6>
                                <p class="mb-1"><strong>From:</strong> {{ $coupon->start_date->format('M d, Y') }}</p>
                                <p class="mb-1"><strong>To:</strong> {{ $coupon->end_date->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-3">
                                <h6>Status</h6>
                                <p class="mb-1">
                                    <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $coupon->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                                @if ($coupon->isExpired())
                                    <span class="badge bg-warning">Expired</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage History -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Usage History</h5>

                        @if ($usage->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Used On</th>
                                            <th>Order Amount</th>
                                            <th>Discount Applied</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($usage as $usageRecord)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded text-white me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $usageRecord->user->name }}</h6>
                                                            <small class="text-muted">User ID:
                                                                {{ $usageRecord->user_id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $usageRecord->user->email }}</td>
                                                <td>{{ $usageRecord->used_at->format('M d, Y h:i A') }}</td>
                                                <td>
                                                    <!-- You can add order amount here if you have the relationship -->
                                                    <span class="text-muted">N/A</span>
                                                </td>
                                                <td>
                                                    <!-- You can add discount amount here if you have the relationship -->
                                                    <span class="text-muted">N/A</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $usage->links('pagination::bootstrap-5') }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No Usage History</h4>
                                <p class="text-muted">This coupon hasn't been used by any customers yet.</p>
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back to Coupons
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
