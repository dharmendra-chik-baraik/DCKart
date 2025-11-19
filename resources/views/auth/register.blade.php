@extends('layouts.guest')

@section('title', 'Register')
@section('page-title', 'Create Account')
@section('page-subtitle', 'Join our multi-vendor platform')

@section('content')
<form method="POST" action="#">
    @csrf

    <div class="mb-3">
        <label for="role" class="form-label">Register As</label>
        <select id="role" name="role" class="form-select" required>
            <option value="customer">Customer</option>
            <option value="vendor">Vendor</option>
        </select>
    </div>

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

    <!-- Vendor Specific Fields -->
    <div id="vendor-fields" class="mb-3" style="display: none;">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="shop_name" class="form-label">Shop Name</label>
                    <input type="text" class="form-control" id="shop_name" name="shop_name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="#" class="text-decoration-none">Already registered?</a>
        <button type="submit" class="btn btn-primary">Register</button>
    </div>
</form>

<script>
    document.getElementById('role').addEventListener('change', function() {
        const vendorFields = document.getElementById('vendor-fields');
        if (this.value === 'vendor') {
            vendorFields.style.display = 'block';
        } else {
            vendorFields.style.display = 'none';
        }
    });
</script>
@endsection