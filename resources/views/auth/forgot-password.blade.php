@extends('layouts.guest')

@section('title', 'Forgot Password')
@section('page-title', 'Reset Password')
@section('page-subtitle', 'Enter your email to receive reset link')

@section('content')
<form method="POST" action="#">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <a href="#" class="text-decoration-none">Back to login</a>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </div>
</form>
@endsection