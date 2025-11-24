@extends('admin.layouts.app')

@section('title', 'Email Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-envelope me-2"></i>Email Settings
                    </h3>
                    <a href="{{ route('admin.settings.index') }}" class="link btn btn-primary float-end"><i class="fas fa-arrow-left me-2"></i>Back to Settings</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update-email') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Email Configuration -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-cog me-2"></i>Email Configuration
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- From Address -->
                                        <div class="form-group mb-3">
                                            <label for="mail_from_address" class="form-label">From Email Address *</label>
                                            <input type="email" name="mail_from_address" id="mail_from_address" 
                                                   class="form-control @error('mail_from_address') is-invalid @enderror" 
                                                   value="{{ old('mail_from_address', $emailSettings['mail_from_address'] ?? '') }}" 
                                                   required>
                                            @error('mail_from_address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- From Name -->
                                        <div class="form-group mb-3">
                                            <label for="mail_from_name" class="form-label">From Name *</label>
                                            <input type="text" name="mail_from_name" id="mail_from_name" 
                                                   class="form-control @error('mail_from_name') is-invalid @enderror" 
                                                   value="{{ old('mail_from_name', $emailSettings['mail_from_name'] ?? '') }}" 
                                                   required>
                                            @error('mail_from_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Notification Settings -->
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-bell me-2"></i>Notification Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Customer Notifications -->
                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" name="customer_order_notifications" id="customer_order_notifications" 
                                                       class="form-check-input" value="1"
                                                       {{ (old('customer_order_notifications', $emailSettings['customer_order_notifications'] ?? false)) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="customer_order_notifications">
                                                    Customer Order Notifications
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Send email notifications to customers for order updates
                                            </small>
                                        </div>

                                        <!-- Vendor Notifications -->
                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" name="vendor_order_notifications" id="vendor_order_notifications" 
                                                       class="form-check-input" value="1"
                                                       {{ (old('vendor_order_notifications', $emailSettings['vendor_order_notifications'] ?? false)) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="vendor_order_notifications">
                                                    Vendor Order Notifications
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Send email notifications to vendors for new orders
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
                                            <i class="fas fa-info-circle me-2"></i>Email Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            <strong>SMTP Configuration:</strong><br>
                                            For full email functionality, configure your SMTP settings in the <code>.env</code> file.
                                        </p>
                                        <hr>
                                        <p class="card-text">
                                            <strong>Required .env variables:</strong>
                                        </p>
                                        <code class="d-block bg-light p-2 rounded small">
MAIL_MAILER=smtp<br>
MAIL_HOST=your-smtp-host<br>
MAIL_PORT=587<br>
MAIL_USERNAME=your-email<br>
MAIL_PASSWORD=your-password<br>
MAIL_ENCRYPTION=tls
                                        </code>
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
                                            <i class="fas fa-save me-2"></i>Save Email Settings
                                        </button>
                                        <a href="{{ route('admin.settings.general') }}" class="btn btn-secondary btn-lg ms-2">
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