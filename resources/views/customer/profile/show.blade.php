@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 border">
            @include('customer.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">My Profile</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <a href="{{ route('customer.profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Full Name:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $user->name }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Email Address:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Phone Number:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $user->phone ?? 'Not provided' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Member Since:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $user->created_at->format('F d, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Account Status -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Account Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <span class="badge bg-success float-end">{{ ucfirst($user->status) }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Role:</strong>
                                <span class="badge bg-info float-end">{{ ucfirst($user->role) }}</span>
                            </div>
                            <div class="mb-0">
                                <strong>Last Login:</strong>
                                <span class="text-muted float-end">
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        Never
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('customer.profile.edit') }}" class="btn btn-outline-primary text-start">
                                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                                </a>
                                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-info text-start">
                                    <i class="fas fa-shopping-bag me-2"></i>View Orders
                                </a>
                                <a href="{{ route('customer.addresses.index') }}" class="btn btn-outline-warning text-start">
                                    <i class="fas fa-address-book me-2"></i>Manage Addresses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-primary mb-1">{{ $user->orders()->count() }}</h5>
                                        <small class="text-muted">Total Orders</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-warning mb-1">{{ $user->orders()->where('order_status', 'pending')->count() }}</h5>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-success mb-1">{{ $user->orders()->where('order_status', 'delivered')->count() }}</h5>
                                        <small class="text-muted">Delivered</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h5 class="text-info mb-1">₹{{ number_format($user->orders()->where('payment_status', 'completed')->sum('grand_total'), 2) }}</h5>
                                    <small class="text-muted">Total Spent</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection