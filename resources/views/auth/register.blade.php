@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="dc-login my-5 d-flex justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">Create Account</h3>
                    <p class="mb-0 opacity-75">Join our platform today</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('agree_terms') is-invalid @enderror" 
                                   id="agree_terms" name="agree_terms" value="1" {{ old('agree_terms') ? 'checked' : '' }}>
                            <label class="form-check-label" for="agree_terms">
                                I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a>
                            </label>
                            @error('agree_terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('login') }}" class="text-decoration-none">Already have an account?</a>
                            <a href="{{ route('vendor.register') }}" class="text-decoration-none">Register as Vendor</a>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.dc-login {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 75vh;
}
</style>
@endpush