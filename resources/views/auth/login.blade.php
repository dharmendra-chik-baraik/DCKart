@extends('layouts.guest')

@section('title', 'Login')
@section('page-title', 'Welcome Back')
@section('page-subtitle', 'Sign in to your account')

@section('content')
    <form method="POST" action="#">
        @csrf

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-3">
            <label for="role" class="form-label">Login As</label>
            <select id="role" name="role" class="form-select" required>
                <option value="customer">Customer</option>
                <option value="vendor">Vendor</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="#" class="text-decoration-none">Forgot your password?</a>
            <button type="submit" class="btn btn-primary">Log in</button>
        </div>

        <div class="text-center">
            <p class="mb-0">Don't have an account?
                <a href="#" class="text-decoration-none">Register here</a>
            </p>
        </div>
    </form>
@endsection
