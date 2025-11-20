<!-- resources/views/admin/vendors/create.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Create New Vendor')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create New Vendor</h1>
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Vendors
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Vendor Information</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.vendors.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- User Account Information -->
                                <div class="col-md-6">
                                    <h5 class="mb-3 text-primary">User Account Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Vendor Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
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
                                        <label for="password" class="form-label">Password *</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="user_status" class="form-label">User Account Status *</label>
                                        <select class="form-control @error('user_status') is-invalid @enderror" id="user_status"
                                            name="user_status" required>
                                            <option value="active" {{ old('user_status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('user_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="suspended" {{ old('user_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        </select>
                                        @error('user_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Controls user login access</small>
                                    </div>
                                </div>

                                <!-- Shop Information -->
                                <div class="col-md-6">
                                    <h5 class="mb-3 text-primary">Shop Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="shop_name" class="form-label">Shop Name *</label>
                                        <input type="text" class="form-control @error('shop_name') is-invalid @enderror"
                                            id="shop_name" name="shop_name" value="{{ old('shop_name') }}" required>
                                        @error('shop_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="shop_slug" class="form-label">Shop Slug *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">/</span>
                                            <input type="text" class="form-control @error('shop_slug') is-invalid @enderror"
                                                id="shop_slug" name="shop_slug" value="{{ old('shop_slug') }}" required>
                                            @error('shop_slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="form-text text-muted">This will be used in the vendor's store URL</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number *</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="vendor_status" class="form-label">Vendor Business Status *</label>
                                        <select class="form-control @error('vendor_status') is-invalid @enderror"
                                            id="vendor_status" name="vendor_status" required>
                                            <option value="pending" {{ old('vendor_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ old('vendor_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="suspended" {{ old('vendor_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            <option value="rejected" {{ old('vendor_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        @error('vendor_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Controls vendor selling permissions</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Address Information -->
                            <h5 class="mb-3 text-primary">Address Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Address *</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address" value="{{ old('address') }}" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="state" class="form-label">State *</label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror"
                                        id="state" name="state" value="{{ old('state') }}" required>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country *</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                        id="country" name="country" value="{{ old('country') }}" required>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="pincode" class="form-label">Pincode *</label>
                                    <input type="text" class="form-control @error('pincode') is-invalid @enderror"
                                        id="pincode" name="pincode" value="{{ old('pincode') }}" required>
                                    @error('pincode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Business Details -->
                            <h5 class="mb-3 text-primary">Business Details (Optional)</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gst_number" class="form-label">GST Number</label>
                                    <input type="text" class="form-control @error('gst_number') is-invalid @enderror"
                                        id="gst_number" name="gst_number" value="{{ old('gst_number') }}">
                                    @error('gst_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="pan_number" class="form-label">PAN Number</label>
                                    <input type="text" class="form-control @error('pan_number') is-invalid @enderror"
                                        id="pan_number" name="pan_number" value="{{ old('pan_number') }}">
                                    @error('pan_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Bank Details -->
                            <h5 class="mb-3 text-primary">Bank Details (Optional)</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bank_account_number" class="form-label">Bank Account Number</label>
                                    <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror"
                                        id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}">
                                    @error('bank_account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                        id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="branch" class="form-label">Branch</label>
                                    <input type="text" class="form-control @error('branch') is-invalid @enderror"
                                        id="branch" name="branch" value="{{ old('branch') }}">
                                    @error('branch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="ifsc_code" class="form-label">IFSC Code</label>
                                    <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror"
                                        id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}">
                                    @error('ifsc_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Shop Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Verification -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified"
                                            value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_verified">
                                            Mark vendor as verified
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Verified vendors get a verified badge and higher trust score</small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Vendor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate slug from shop name
        document.getElementById('shop_name').addEventListener('input', function() {
            const slugInput = document.getElementById('shop_slug');
            if (!slugInput.value) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                slugInput.value = slug;
            }
        });
    </script>
@endsection