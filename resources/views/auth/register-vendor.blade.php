@extends('layouts.app')

@section('title', 'Become a Vendor')

@section('content')
<div class="container">
    <div class="dc-login my-5 d-flex justify-content-center">
        <div class="col-md-8 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">Become a Vendor</h3>
                    <p class="mb-0 opacity-75">Start selling on our platform</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('vendor.register') }}">
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

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Personal Information</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Shop Information</h5>

                                <div class="mb-3">
                                    <label for="shop_name" class="form-label">Shop Name *</label>
                                    <input type="text" class="form-control @error('shop_name') is-invalid @enderror" 
                                           id="shop_name" name="shop_name" value="{{ old('shop_name') }}" required>
                                    @error('shop_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="shop_address" class="form-label">Shop Address *</label>
                                    <textarea class="form-control @error('shop_address') is-invalid @enderror" 
                                              id="shop_address" name="shop_address" rows="2" required>{{ old('shop_address') }}</textarea>
                                    @error('shop_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shop_city" class="form-label">City *</label>
                                            <input type="text" class="form-control @error('shop_city') is-invalid @enderror" 
                                                   id="shop_city" name="shop_city" value="{{ old('shop_city') }}" required>
                                            @error('shop_city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shop_state" class="form-label">State *</label>
                                            <input type="text" class="form-control @error('shop_state') is-invalid @enderror" 
                                                   id="shop_state" name="shop_state" value="{{ old('shop_state') }}" required>
                                            @error('shop_state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="shop_pincode" class="form-label">Pincode *</label>
                                    <input type="text" class="form-control @error('shop_pincode') is-invalid @enderror" 
                                           id="shop_pincode" name="shop_pincode" value="{{ old('shop_pincode') }}" required>
                                    @error('shop_pincode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Account Security</h5>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Business Details</h5>

                                <div class="mb-3">
                                    <label for="gst_number" class="form-label">GST Number</label>
                                    <input type="text" class="form-control @error('gst_number') is-invalid @enderror" 
                                           id="gst_number" name="gst_number" value="{{ old('gst_number') }}">
                                    @error('gst_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="pan_number" class="form-label">PAN Number</label>
                                    <input type="text" class="form-control @error('pan_number') is-invalid @enderror" 
                                           id="pan_number" name="pan_number" value="{{ old('pan_number') }}">
                                    @error('pan_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('agree_terms') is-invalid @enderror" 
                                   id="agree_terms" name="agree_terms" value="1" {{ old('agree_terms') ? 'checked' : '' }}>
                            <label class="form-check-label" for="agree_terms">
                                I agree to the <a href="#" class="text-decoration-none">Vendor Terms and Conditions</a>
                            </label>
                            @error('agree_terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('register') }}" class="text-decoration-none">Register as Customer</a>
                            <button type="submit" class="btn btn-success px-4">Apply as Vendor</button>
                        </div>

                        <div class="alert alert-info mt-3">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Note:</strong> Your vendor account will be reviewed and approved by our team. 
                                You'll receive an email once your account is activated.
                            </small>
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