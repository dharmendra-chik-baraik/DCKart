@extends('layouts.app')

@section('title', 'Edit Address')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 border">
            @include('customer.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Edit Address</h4>
                <a href="{{ route('customer.addresses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Addresses
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('customer.addresses.update', $address->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $address->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $address->phone) }}" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address_line_1" class="form-label">Address Line 1 *</label>
                            <input type="text" 
                                   class="form-control @error('address_line_1') is-invalid @enderror" 
                                   id="address_line_1" 
                                   name="address_line_1" 
                                   value="{{ old('address_line_1', $address->address_line_1) }}" 
                                   required>
                            @error('address_line_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address_line_2" class="form-label">Address Line 2</label>
                            <input type="text" 
                                   class="form-control @error('address_line_2') is-invalid @enderror" 
                                   id="address_line_2" 
                                   name="address_line_2" 
                                   value="{{ old('address_line_2', $address->address_line_2) }}">
                            @error('address_line_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" 
                                           class="form-control @error('city') is-invalid @enderror" 
                                           id="city" 
                                           name="city" 
                                           value="{{ old('city', $address->city) }}" 
                                           required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State *</label>
                                    <input type="text" 
                                           class="form-control @error('state') is-invalid @enderror" 
                                           id="state" 
                                           name="state" 
                                           value="{{ old('state', $address->state) }}" 
                                           required>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pincode" class="form-label">Pincode *</label>
                                    <input type="text" 
                                           class="form-control @error('pincode') is-invalid @enderror" 
                                           id="pincode" 
                                           name="pincode" 
                                           value="{{ old('pincode', $address->pincode) }}" 
                                           required>
                                    @error('pincode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Address Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Select Type</option>
                                        <option value="home" {{ old('type', $address->type) == 'home' ? 'selected' : '' }}>Home</option>
                                        <option value="work" {{ old('type', $address->type) == 'work' ? 'selected' : '' }}>Work</option>
                                        <option value="other" {{ old('type', $address->type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_default" 
                                               name="is_default" 
                                               value="1" 
                                               {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_default">
                                            Set as default address
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('customer.addresses.index') }}" class="btn btn-secondary me-md-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Address
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection