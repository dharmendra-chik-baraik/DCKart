@extends('layouts.app')

@section('title', 'Vendors - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold text-dark mb-3">Our Trusted Vendors</h1>
            <p class="text-muted lead">Shop from verified and trusted sellers</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($vendors as $vendor)
        <div class="col-lg-4 col-md-6">
            <div class="card vendor-card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="vendor-logo mb-4">
                        @if($vendor->logo)
                            <img src="{{ $vendor->logo }}" alt="{{ $vendor->shop_name }}" 
                                 class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-store fa-2x text-primary"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-2">{{ $vendor->shop_name }}</h4>
                    <p class="text-muted small mb-3">{{ Str::limit($vendor->description, 100) }}</p>
                    
                    <div class="vendor-stats mb-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="fw-bold text-primary">{{ $vendor->products_count }}</div>
                                <small class="text-muted">Products</small>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-success">
                                    <i class="fas fa-star text-warning"></i> 4.8
                                </div>
                                <small class="text-muted">Rating</small>
                            </div>
                        </div>
                    </div>

                    <div class="vendor-actions">
                        <a href="{{ route('vendors.show', $vendor->shop_slug) }}" class="btn btn-outline-primary btn-sm me-2">
                            View Store
                        </a>
                        <a href="{{ route('vendors.products', $vendor->shop_slug) }}" class="btn btn-primary btn-sm">
                            View Products
                        </a>
                    </div>

                    @if($vendor->verified_at)
                    <div class="mt-3">
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Verified
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <div class="text-center py-5">
                <i class="fas fa-store fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No vendors available</h4>
                <p class="text-muted">Vendors will be added soon</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-5">
        {{ $vendors->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection