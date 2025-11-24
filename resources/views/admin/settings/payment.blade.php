@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-credit-card me-2"></i>Payment Settings
                        </h3>
                        <a href="{{ route('admin.settings.index') }}" class="link btn btn-primary float-end"><i
                                class="fas fa-arrow-left me-2"></i>Back to Settings</a>

                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.update-payment') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Payment Methods -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-wallet me-2"></i>Payment Methods
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <!-- Available Payment Gateways -->
                                            <div class="form-group mb-4">
                                                <label class="form-label fw-semibold">Available Payment Gateways *</label>
                                                @php
                                                    $selectedGateways = old(
                                                        'payment_gateways',
                                                        $paymentSettings['payment_gateways'] ?? ['cash_on_delivery'],
                                                    );
                                                @endphp

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" name="payment_gateways[]" id="cod"
                                                                class="form-check-input" value="cash_on_delivery"
                                                                {{ in_array('cash_on_delivery', $selectedGateways) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="cod">
                                                                <i class="fas fa-money-bill-wave me-1"></i>Cash on Delivery
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" name="payment_gateways[]" id="stripe"
                                                                class="form-check-input" value="stripe"
                                                                {{ in_array('stripe', $selectedGateways) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="stripe">
                                                                <i class="fab fa-stripe me-1"></i>Stripe
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" name="payment_gateways[]" id="razorpay"
                                                                class="form-check-input" value="razorpay"
                                                                {{ in_array('razorpay', $selectedGateways) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="razorpay">
                                                                <i class="fas fa-rupee-sign me-1"></i>Razorpay
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" name="payment_gateways[]" id="paypal"
                                                                class="form-check-input" value="paypal"
                                                                {{ in_array('paypal', $selectedGateways) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="paypal">
                                                                <i class="fab fa-paypal me-1"></i>PayPal
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @error('payment_gateways')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Default Payment Method -->
                                            <div class="form-group mb-3">
                                                <label for="default_payment_method" class="form-label">Default Payment
                                                    Method *</label>
                                                <select name="default_payment_method" id="default_payment_method"
                                                    class="form-control @error('default_payment_method') is-invalid @enderror"
                                                    required>
                                                    <option value="cash_on_delivery"
                                                        {{ old('default_payment_method', $paymentSettings['default_payment_method'] ?? '') == 'cash_on_delivery' ? 'selected' : '' }}>
                                                        Cash on Delivery</option>
                                                    <option value="stripe"
                                                        {{ old('default_payment_method', $paymentSettings['default_payment_method'] ?? '') == 'stripe' ? 'selected' : '' }}>
                                                        Stripe</option>
                                                    <option value="razorpay"
                                                        {{ old('default_payment_method', $paymentSettings['default_payment_method'] ?? '') == 'razorpay' ? 'selected' : '' }}>
                                                        Razorpay</option>
                                                    <option value="paypal"
                                                        {{ old('default_payment_method', $paymentSettings['default_payment_method'] ?? '') == 'paypal' ? 'selected' : '' }}>
                                                        PayPal</option>
                                                </select>
                                                @error('default_payment_method')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Test Mode -->
                                            <div class="form-group mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" name="payment_test_mode" id="payment_test_mode"
                                                        class="form-check-input" value="1"
                                                        {{ old('payment_test_mode', $paymentSettings['payment_test_mode'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="payment_test_mode">
                                                        Payment Test Mode
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">
                                                    Enable test mode for payment gateways (no real transactions)
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Information Sidebar -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-info-circle me-2"></i>Payment Gateway Setup
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">
                                                <strong>Stripe Configuration:</strong><br>
                                                Add these to your <code>.env</code> file:
                                            </p>
                                            <code class="d-block bg-light p-2 rounded small mb-3">
                                                STRIPE_KEY=your-publishable-key<br>
                                                STRIPE_SECRET=your-secret-key
                                            </code>

                                            <p class="card-text">
                                                <strong>Razorpay Configuration:</strong>
                                            </p>
                                            <code class="d-block bg-light p-2 rounded small">
                                                RAZORPAY_KEY=your-key-id<br>
                                                RAZORPAY_SECRET=your-secret-key
                                            </code>

                                            <hr>
                                            <div class="alert alert-warning small mb-0">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>Important:</strong> Never commit actual API keys to version control.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save me-2"></i>Save Payment Settings
                                            </button>
                                            <a href="{{ route('admin.settings.general') }}"
                                                class="btn btn-secondary btn-lg ms-2">
                                                <i class="fas fa-arrow-left me-2"></i>Back to General
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
