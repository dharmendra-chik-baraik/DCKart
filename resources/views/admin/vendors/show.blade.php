<!-- resources/views/admin/vendors/show.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Vendor Details: ' . $vendor->shop_name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Vendor Details</h1>
        <div class="btn-group">
            <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Vendors
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Vendor Information -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vendor Information</h6>
                </div>
                <div class="card-body text-center">
                    <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; font-size: 24px;">
                        {{ strtoupper(substr($vendor->user->name, 0, 2)) }}
                    </div>
                    <h4>{{ $vendor->user->name }}</h4>
                    <p class="text-muted">{{ $vendor->user->email }}</p>
                    
                    <!-- User Status -->
                    <div class="row text-start mt-4">
                        <div class="col-6">
                            <strong>User Status:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge 
                                @if($vendor->user->status == 'active') bg-success
                                @elseif($vendor->user->status == 'inactive') bg-secondary
                                @elseif($vendor->user->status == 'suspended') bg-danger
                                @else bg-secondary @endif">
                                {{ ucfirst($vendor->user->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Business Status -->
                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Business Status:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge 
                                @if($vendor->status == 'approved') bg-success
                                @elseif($vendor->status == 'pending') bg-warning
                                @elseif($vendor->status == 'suspended') bg-danger
                                @elseif($vendor->status == 'rejected') bg-secondary
                                @else bg-secondary @endif">
                                {{ ucfirst($vendor->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Verification Status -->
                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Verification:</strong>
                        </div>
                        <div class="col-6">
                            @if($vendor->verified_at)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Verified
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Phone:</strong>
                        </div>
                        <div class="col-6">
                            {{ $vendor->phone ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Registered:</strong>
                        </div>
                        <div class="col-6">
                            {{ $vendor->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <!-- Toggle Verification -->
                        <form action="{{ route('admin.vendors.toggle-verification', $vendor->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-{{ $vendor->verified_at ? 'warning' : 'success' }} w-100 mb-2">
                                <i class="fas {{ $vendor->verified_at ? 'fa-times' : 'fa-check' }} me-2"></i>
                                {{ $vendor->verified_at ? 'Unverify Vendor' : 'Verify Vendor' }}
                            </button>
                        </form>
                        
                        <!-- Business Status Actions -->
                        @if($vendor->status != 'approved')
                        <form action="{{ route('admin.vendors.change-status', $vendor->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-check me-2"></i>Approve Business
                            </button>
                        </form>
                        @endif

                        @if($vendor->status != 'suspended')
                        <form action="{{ route('admin.vendors.change-status', $vendor->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="suspended">
                            <button type="submit" class="btn btn-warning w-100 mb-2">
                                <i class="fas fa-pause me-2"></i>Suspend Business
                            </button>
                        </form>
                        @endif

                        @if($vendor->status != 'rejected')
                        <form action="{{ route('admin.vendors.change-status', $vendor->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger w-100 mb-2">
                                <i class="fas fa-times me-2"></i>Reject Business
                            </button>
                        </form>
                        @endif

                        <!-- User Status Actions -->
                        @if($vendor->user->status != 'active')
                        <form action="{{ route('admin.users.change-status', $vendor->user->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-user-check me-2"></i>Activate User
                            </button>
                        </form>
                        @endif

                        @if($vendor->user->status != 'suspended')
                        <form action="{{ route('admin.users.change-status', $vendor->user->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="suspended">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-user-slash me-2"></i>Suspend User
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendor Details & Stats -->
        <div class="col-md-8">
            <!-- Shop Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Shop Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ $vendor->shop_name }}</h5>
                            <p class="text-muted">Slug: /{{ $vendor->shop_slug }}</p>
                            
                            @if($vendor->description)
                            <p><strong>Description:</strong></p>
                            <p class="text-muted">{{ $vendor->description }}</p>
                            @endif

                            <!-- Business Details -->
                            @if($vendor->gst_number || $vendor->pan_number)
                            <p><strong>Business Details:</strong></p>
                            <ul class="list-unstyled">
                                @if($vendor->gst_number)
                                <li><small class="text-muted">GST: {{ $vendor->gst_number }}</small></li>
                                @endif
                                @if($vendor->pan_number)
                                <li><small class="text-muted">PAN: {{ $vendor->pan_number }}</small></li>
                                @endif
                            </ul>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Store URL:</strong></p>
                            <code>{{ url('/vendor/' . $vendor->shop_slug) }}</code>
                            
                            <p class="mt-3"><strong>Contact Information:</strong></p>
                            <p>
                                <i class="fas fa-user me-2 text-muted"></i>{{ $vendor->user->name }}<br>
                                <i class="fas fa-envelope me-2 text-muted"></i>{{ $vendor->user->email }}<br>
                                <i class="fas fa-phone me-2 text-muted"></i>{{ $vendor->phone ?? 'N/A' }}
                            </p>

                            <!-- Address -->
                            @if($vendor->address)
                            <p class="mt-3"><strong>Address:</strong></p>
                            <p class="text-muted small">
                                {{ $vendor->address }}<br>
                                @if($vendor->city){{ $vendor->city }}, @endif
                                @if($vendor->state){{ $vendor->state }}<br>@endif
                                @if($vendor->country){{ $vendor->country }} @endif
                                @if($vendor->pincode)- {{ $vendor->pincode }}@endif
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Details -->
            @if($vendor->bank_account_number || $vendor->bank_name)
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bank Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($vendor->bank_account_number)
                        <div class="col-md-6">
                            <strong>Account Number:</strong><br>
                            <span class="text-muted">{{ $vendor->bank_account_number }}</span>
                        </div>
                        @endif
                        @if($vendor->bank_name)
                        <div class="col-md-6">
                            <strong>Bank Name:</strong><br>
                            <span class="text-muted">{{ $vendor->bank_name }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="row mt-2">
                        @if($vendor->branch)
                        <div class="col-md-6">
                            <strong>Branch:</strong><br>
                            <span class="text-muted">{{ $vendor->branch }}</span>
                        </div>
                        @endif
                        @if($vendor->ifsc_code)
                        <div class="col-md-6">
                            <strong>IFSC Code:</strong><br>
                            <span class="text-muted">{{ $vendor->ifsc_code }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Vendor Stats -->
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vendor Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary">{{ $stats['total_products'] ?? 0 }}</h4>
                                <small class="text-muted">Total Products</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success">{{ $stats['total_orders'] ?? 0 }}</h4>
                                <small class="text-muted">Total Orders</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info">{{ $stats['pending_orders'] ?? 0 }}</h4>
                                <small class="text-muted">Pending Orders</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h4>
                                <small class="text-muted">Total Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            @if($vendor->products && $vendor->products->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Products</h6>
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendor->products->take(5) as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->images->count() > 0)
                                            <img src="{{ $product->images->first()->image_path }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $product->name }}</strong><br>
                                                <small class="text-muted">{{ $product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($product->final_price, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $product->stock_status == 'in_stock' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $product->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection