@extends('admin.layouts.app')

@section('title', 'Coupon Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center mb-4 ">
                <h1 class="h3 mb-0 text-gray-800 page-title">Coupon Management</h1>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="fas fa-ticket-alt widget-icon text-primary"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Total Coupons</h5>
                    <h3 class="mt-3 mb-3">{{ $stats['total_coupons'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="fas fa-check-circle widget-icon text-success"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Active Coupons</h5>
                    <h3 class="mt-3 mb-3">{{ $stats['active_coupons'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="fas fa-clock widget-icon text-warning"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Expired Coupons</h5>
                    <h3 class="mt-3 mb-3">{{ $stats['expired_coupons'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="fas fa-users widget-icon text-info"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Total Usage</h5>
                    <h3 class="mt-3 mb-3">{{ $stats['total_usage'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <form method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="Search coupon code..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.coupons.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Create Coupon
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($coupons->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Coupon Code</th>
                                    <th>Discount</th>
                                    <th>Min Order</th>
                                    <th>Usage</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coupons as $coupon)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded text-white me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-ticket-alt"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">
                                                    <code>{{ $coupon->code }}</code>
                                                </h6>
                                                <small class="text-muted text-capitalize">
                                                    {{ str_replace('_', ' ', $coupon->discount_type) }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($coupon->discount_type === 'percentage')
                                            <span class="fw-bold text-primary">{{ $coupon->discount_value }}%</span>
                                            @if($coupon->max_discount)
                                                <br><small class="text-muted">Max: ₹{{ number_format($coupon->max_discount, 2) }}</small>
                                            @endif
                                        @else
                                            <span class="fw-bold text-primary">₹{{ number_format($coupon->discount_value, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        ₹{{ number_format($coupon->min_order_value, 2) }}
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="fw-bold">{{ $coupon->used_count }}</span>
                                            @if($coupon->usage_limit)
                                                <small class="d-block text-muted">/ {{ $coupon->usage_limit }}</small>
                                            @else
                                                <small class="d-block text-muted">Unlimited</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <small class="d-block">
                                                <strong>From:</strong> {{ $coupon->start_date->format('M d, Y') }}
                                            </small>
                                            <small class="d-block">
                                                <strong>To:</strong> {{ $coupon->end_date->format('M d, Y') }}
                                            </small>
                                            @if($coupon->end_date->isPast())
                                                <span class="badge bg-danger mt-1">Expired</span>
                                            @elseif($coupon->start_date->isFuture())
                                                <span class="badge bg-warning mt-1">Upcoming</span>
                                            @else
                                                <span class="badge bg-success mt-1">Active</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $coupon->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $coupon->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group-sm">
                                            <a href="{{ route('admin.coupons.usage', $coupon->id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="View Usage">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Edit Coupon">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.coupons.toggle-status', $coupon->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $coupon->status ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                        title="{{ $coupon->status ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas {{ $coupon->status ? 'fa-times' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Coupon">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $coupons->links('pagination::bootstrap-5') }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Coupons Found</h4>
                        <p class="text-muted">Get started by creating your first coupon.</p>
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Coupon
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection