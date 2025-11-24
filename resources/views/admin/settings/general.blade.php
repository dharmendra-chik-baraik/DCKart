@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog me-2"></i>General Settings
                        </h3>
                        <a href="{{ route('admin.settings.index') }}" class="link btn btn-primary float-end"><i
                                class="fas fa-arrow-left me-2"></i>Back to Settings</a>

                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.update-general') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Site Information -->
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-info-circle me-2"></i>Site Information
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <!-- Site Name -->
                                            <div class="form-group mb-3">
                                                <label for="site_name" class="form-label">Site Name *</label>
                                                <input type="text" name="site_name" id="site_name"
                                                    class="form-control @error('site_name') is-invalid @enderror"
                                                    value="{{ old('site_name', $settings['general']['site_name'] ?? '') }}"
                                                    required>
                                                @error('site_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Site Email -->
                                            <div class="form-group mb-3">
                                                <label for="site_email" class="form-label">Site Email *</label>
                                                <input type="email" name="site_email" id="site_email"
                                                    class="form-control @error('site_email') is-invalid @enderror"
                                                    value="{{ old('site_email', $settings['general']['site_email'] ?? '') }}"
                                                    required>
                                                @error('site_email')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Site Phone -->
                                            <div class="form-group mb-3">
                                                <label for="site_phone" class="form-label">Site Phone</label>
                                                <input type="text" name="site_phone" id="site_phone"
                                                    class="form-control @error('site_phone') is-invalid @enderror"
                                                    value="{{ old('site_phone', $settings['general']['site_phone'] ?? '') }}">
                                                @error('site_phone')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Currency & Timezone -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="site_currency" class="form-label">Currency *</label>
                                                        <select name="site_currency" id="site_currency"
                                                            class="form-control @error('site_currency') is-invalid @enderror"
                                                            required>
                                                            <option value="INR"
                                                                {{ old('site_currency', $settings['general']['site_currency'] ?? '') == 'INR' ? 'selected' : '' }}>
                                                                INR - Indian Rupee</option>
                                                            <option value="USD"
                                                                {{ old('site_currency', $settings['general']['site_currency'] ?? '') == 'USD' ? 'selected' : '' }}>
                                                                USD - US Dollar</option>
                                                            <option value="EUR"
                                                                {{ old('site_currency', $settings['general']['site_currency'] ?? '') == 'EUR' ? 'selected' : '' }}>
                                                                EUR - Euro</option>
                                                        </select>
                                                        @error('site_currency')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="site_timezone" class="form-label">Timezone *</label>
                                                        <select name="site_timezone" id="site_timezone"
                                                            class="form-control @error('site_timezone') is-invalid @enderror"
                                                            required>
                                                            <option value="Asia/Kolkata"
                                                                {{ old('site_timezone', $settings['general']['site_timezone'] ?? '') == 'Asia/Kolkata' ? 'selected' : '' }}>
                                                                Asia/Kolkata (IST)</option>
                                                            <option value="UTC"
                                                                {{ old('site_timezone', $settings['general']['site_timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>
                                                                UTC</option>
                                                            <option value="America/New_York"
                                                                {{ old('site_timezone', $settings['general']['site_timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>
                                                                America/New York</option>
                                                        </select>
                                                        @error('site_timezone')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Maintenance Mode -->
                                            <div class="form-group mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode"
                                                        class="form-check-input" value="1"
                                                        {{ old('maintenance_mode', $settings['general']['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="maintenance_mode">
                                                        Maintenance Mode
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">
                                                    When enabled, the site will be unavailable to visitors
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ecommerce Settings -->
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-shopping-cart me-2"></i>Ecommerce Settings
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <!-- Currency Settings -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="currency_symbol" class="form-label">Currency Symbol
                                                            *</label>
                                                        <input type="text" name="currency_symbol" id="currency_symbol"
                                                            class="form-control @error('currency_symbol') is-invalid @enderror"
                                                            value="{{ old('currency_symbol', $settings['ecommerce']['currency_symbol'] ?? '₹') }}"
                                                            required maxlength="5">
                                                        @error('currency_symbol')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="currency_position" class="form-label">Currency
                                                            Position *</label>
                                                        <select name="currency_position" id="currency_position"
                                                            class="form-control @error('currency_position') is-invalid @enderror"
                                                            required>
                                                            <option value="left"
                                                                {{ old('currency_position', $settings['ecommerce']['currency_position'] ?? '') == 'left' ? 'selected' : '' }}>
                                                                Left ($100)</option>
                                                            <option value="right"
                                                                {{ old('currency_position', $settings['ecommerce']['currency_position'] ?? '') == 'right' ? 'selected' : '' }}>
                                                                Right (100$)</option>
                                                        </select>
                                                        @error('currency_position')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Decimal Points -->
                                            <div class="form-group mb-3">
                                                <label for="decimal_points" class="form-label">Decimal Points *</label>
                                                <input type="number" name="decimal_points" id="decimal_points"
                                                    class="form-control @error('decimal_points') is-invalid @enderror"
                                                    value="{{ old('decimal_points', $settings['ecommerce']['decimal_points'] ?? 2) }}"
                                                    min="0" max="4" required>
                                                @error('decimal_points')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Low Stock Threshold -->
                                            <div class="form-group mb-3">
                                                <label for="low_stock_threshold" class="form-label">Low Stock Threshold
                                                    *</label>
                                                <input type="number" name="low_stock_threshold" id="low_stock_threshold"
                                                    class="form-control @error('low_stock_threshold') is-invalid @enderror"
                                                    value="{{ old('low_stock_threshold', $settings['ecommerce']['low_stock_threshold'] ?? 10) }}"
                                                    min="0" required>
                                                @error('low_stock_threshold')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Show low stock warning when product quantity falls below this number
                                                </small>
                                            </div>

                                            <!-- Units -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="weight_unit" class="form-label">Weight Unit *</label>
                                                        <select name="weight_unit" id="weight_unit"
                                                            class="form-control @error('weight_unit') is-invalid @enderror"
                                                            required>
                                                            <option value="kg"
                                                                {{ old('weight_unit', $settings['ecommerce']['weight_unit'] ?? '') == 'kg' ? 'selected' : '' }}>
                                                                Kilograms (kg)</option>
                                                            <option value="g"
                                                                {{ old('weight_unit', $settings['ecommerce']['weight_unit'] ?? '') == 'g' ? 'selected' : '' }}>
                                                                Grams (g)</option>
                                                            <option value="lb"
                                                                {{ old('weight_unit', $settings['ecommerce']['weight_unit'] ?? '') == 'lb' ? 'selected' : '' }}>
                                                                Pounds (lb)</option>
                                                        </select>
                                                        @error('weight_unit')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="dimension_unit" class="form-label">Dimension Unit
                                                            *</label>
                                                        <select name="dimension_unit" id="dimension_unit"
                                                            class="form-control @error('dimension_unit') is-invalid @enderror"
                                                            required>
                                                            <option value="cm"
                                                                {{ old('dimension_unit', $settings['ecommerce']['dimension_unit'] ?? '') == 'cm' ? 'selected' : '' }}>
                                                                Centimeters (cm)</option>
                                                            <option value="m"
                                                                {{ old('dimension_unit', $settings['ecommerce']['dimension_unit'] ?? '') == 'm' ? 'selected' : '' }}>
                                                                Meters (m)</option>
                                                            <option value="in"
                                                                {{ old('dimension_unit', $settings['ecommerce']['dimension_unit'] ?? '') == 'in' ? 'selected' : '' }}>
                                                                Inches (in)</option>
                                                        </select>
                                                        @error('dimension_unit')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save me-2"></i>Save General Settings
                                            </button>
                                            <a href="{{ route('admin.dashboard') }}"
                                                class="btn btn-secondary btn-lg ms-2">
                                                <i class="fas fa-times me-2"></i>Cancel
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

@push('styles')
    <style>
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .card-header.bg-light {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
@endpush
