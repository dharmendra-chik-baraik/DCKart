@extends('layouts.app')

@section('title', 'Edit Profile')

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
                <h4 class="mb-0">Profile Settings</h4>
                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Profile Information -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('customer.profile.update') }}">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('customer.profile.password.update') }}">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Member Since:</strong>
                                        <p class="text-muted mb-0">{{ $user->created_at->format('F d, Y') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Last Login:</strong>
                                        <p class="text-muted mb-0">
                                            @if($user->last_login_at)
                                                {{ $user->last_login_at->diffForHumans() }}
                                            @else
                                                Never
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Account Status:</strong>
                                        <span class="badge bg-success">{{ ucfirst($user->status) }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Role:</strong>
                                        <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                    </div>
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