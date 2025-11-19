
@extends('layouts.guest')

@section('title', 'Verify Email')
@section('page-title', 'Verify Your Email')
@section('page-subtitle', 'Please verify your email address to continue')

@section('content')
<div class="text-center">
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            A new verification link has been sent to your email address.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-4">
        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
        <h4>Email Verification Required</h4>
    </div>

    <p class="mb-4">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </p>

    <div class="d-flex justify-content-center gap-3 mb-4">
        <form method="POST" action="#">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
            </button>
        </form>

        <form method="POST" action="#">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </button>
        </form>
    </div>

    <div class="mt-4">
        <p class="text-muted small">
            If you're having trouble receiving the email, please check your spam folder or 
            <a href="#" class="text-decoration-none">contact support</a>.
        </p>
    </div>
</div>
@endsection