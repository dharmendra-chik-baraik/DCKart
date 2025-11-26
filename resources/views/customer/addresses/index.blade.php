@extends('layouts.app')

@section('title', 'My Addresses')

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
                <h4 class="mb-0">My Addresses</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <a href="{{ route('customer.addresses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Address
                    </a>
                </div>
            </div>

            @if($addresses->count() > 0)
                <div class="row">
                    @foreach($addresses as $address)
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 {{ $address->is_default ? 'border-primary' : '' }}">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    {{ ucfirst($address->type) }} Address
                                    @if($address->is_default)
                                        <span class="badge bg-primary ms-2">Default</span>
                                    @endif
                                </h6>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            type="button" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if(!$address->is_default)
                                            <li>
                                                <form action="{{ route('customer.addresses.set-default', $address->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-star me-2"></i>Set as Default
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        <li>
                                            <a href="{{ route('customer.addresses.edit', $address->id) }}" 
                                               class="dropdown-item">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('customer.addresses.destroy', $address->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this address?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <address class="mb-0">
                                    <strong>{{ $address->name }}</strong><br>
                                    {{ $address->address_line_1 }}<br>
                                    @if($address->address_line_2)
                                        {{ $address->address_line_2 }}<br>
                                    @endif
                                    {{ $address->city }}, {{ $address->state }}<br>
                                    {{ $address->pincode }}<br>
                                    {{ $address->country }}<br>
                                    <i class="fas fa-phone me-1"></i> {{ $address->phone }}
                                </address>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-address-book fa-4x text-muted mb-4"></i>
                        <h4>No addresses found</h4>
                        <p class="text-muted mb-4">You haven't added any addresses yet.</p>
                        <a href="{{ route('customer.addresses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Your First Address
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection