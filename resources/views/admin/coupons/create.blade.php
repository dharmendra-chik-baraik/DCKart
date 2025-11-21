@extends('admin.layouts.app')

@section('title', 'Create Coupon')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center mb-4">
                    <h1 class="page-title h3 mb-0 text-gray-800">Create New Coupon</h1>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                            <li class="breadcrumb-item active">Create Coupon</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.coupons.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">Coupon Code <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                            id="code" name="code" value="{{ old('code') }}"
                                            placeholder="e.g., SUMMER2024" required>
                                        <div class="form-text">Leave empty to auto-generate a unique code</div>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_type" class="form-label">Discount Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('discount_type') is-invalid @enderror"
                                            id="discount_type" name="discount_type" required>
                                            <option value="">Select Type</option>
                                            <option value="percentage"
                                                {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage
                                            </option>
                                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                                Fixed Amount</option>
                                        </select>
                                        @error('discount_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_value" class="form-label">Discount Value <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('discount_value') is-invalid @enderror"
                                            id="discount_value" name="discount_value" value="{{ old('discount_value') }}"
                                            min="0.01" required>
                                        <div class="form-text" id="discountHelp">
                                            Enter discount percentage or fixed amount
                                        </div>
                                        @error('discount_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_discount" class="form-label">Maximum Discount</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('max_discount') is-invalid @enderror"
                                            id="max_discount" name="max_discount" value="{{ old('max_discount') }}"
                                            min="0">
                                        <div class="form-text">Maximum discount amount (for percentage discounts only)</div>
                                        @error('max_discount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_order_value" class="form-label">Minimum Order Value <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('min_order_value') is-invalid @enderror"
                                            id="min_order_value" name="min_order_value"
                                            value="{{ old('min_order_value', 0) }}" min="0" required>
                                        <div class="form-text">Minimum cart amount required to use this coupon</div>
                                        @error('min_order_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usage_limit" class="form-label">Usage Limit</label>
                                        <input type="number"
                                            class="form-control @error('usage_limit') is-invalid @enderror" id="usage_limit"
                                            name="usage_limit" value="{{ old('usage_limit') }}" min="1"
                                            placeholder="Unlimited">
                                        <div class="form-text">Maximum number of times this coupon can be used</div>
                                        @error('usage_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                            name="start_date" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}"
                                            required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                            id="end_date" name="end_date" value="{{ old('end_date') }}"
                                            min="{{ date('Y-m-d') }}" required>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="status"
                                                name="status" value="1" checked>
                                            <label class="form-check-label" for="status">Active Coupon</label>
                                        </div>
                                        <div class="form-text">Enable this coupon immediately after creation</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 text-end">
                                    <a href="{{ route('admin.coupons.index') }}"
                                        class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Create Coupon</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const discountType = document.getElementById('discount_type');
            const discountValue = document.getElementById('discount_value');
            const maxDiscount = document.getElementById('max_discount');
            const discountHelp = document.getElementById('discountHelp');

            function updateDiscountHelp() {
                if (discountType.value === 'percentage') {
                    discountHelp.textContent = 'Enter discount percentage (e.g., 10 for 10%)';
                    maxDiscount.parentElement.style.display = 'block';
                } else {
                    discountHelp.textContent = 'Enter fixed discount amount (e.g., 50 for ₹50)';
                    maxDiscount.parentElement.style.display = 'none';
                }
            }

            discountType.addEventListener('change', updateDiscountHelp);
            updateDiscountHelp(); // Initial call

            // Set minimum end date based on start date
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');

            startDate.addEventListener('change', function() {
                endDate.min = this.value;
                if (endDate.value && endDate.value < this.value) {
                    endDate.value = this.value;
                }
            });
        });
    </script>
@endpush
