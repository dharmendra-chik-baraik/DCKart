<!-- resources/views/admin/users/show.blade.php -->
@extends('admin.layouts.app')

@section('title', 'User Details: ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <div class="btn-group">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <!-- User Information -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; font-size: 24px;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="row text-start mt-4">
                        <div class="col-6">
                            <strong>Role:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge 
                                @if($user->role == 'admin') bg-danger
                                @elseif($user->role == 'vendor') bg-warning
                                @else bg-info @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge 
                                @if($user->status == 'active') bg-success
                                @elseif($user->status == 'inactive') bg-secondary
                                @else bg-danger @endif">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Email Verified:</strong>
                        </div>
                        <div class="col-6">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-warning">No</span>
                            @endif
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Registered:</strong>
                        </div>
                        <div class="col-6">
                            {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="col-md-8">
            @if($user->vendorProfile)
            <!-- Vendor Profile -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vendor Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Shop Name:</strong> {{ $user->vendorProfile->shop_name }}<br>
                            <strong>Shop Slug:</strong> {{ $user->vendorProfile->shop_slug }}<br>
                            <strong>Phone:</strong> {{ $user->vendorProfile->phone ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Description:</strong><br>
                            <p class="text-muted">{{ $user->vendorProfile->description ?? 'No description' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary">{{ $user->orders->count() }}</h4>
                                <small class="text-muted">Total Orders</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success">{{ $user->addresses->count() }}</h4>
                                <small class="text-muted">Addresses</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info">{{ $user->cartItems->count() }}</h4>
                                <small class="text-muted">Cart Items</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning">{{ $user->wishlists->count() }}</h4>
                                <small class="text-muted">Wishlist Items</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection