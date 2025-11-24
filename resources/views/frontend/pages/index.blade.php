@extends('layouts.app')

@section('title', 'Information Pages - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Information</li>
                </ol>
            </nav>
            <h1 class="fw-bold text-dark mb-3">Information Center</h1>
            <p class="text-muted lead">Find answers to common questions and learn more about our services</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($pages as $page)
        <div class="col-lg-4 col-md-6">
            <div class="card page-card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="page-icon mb-4">
                        <div class="bg-primary rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-file-alt fa-2x text-white"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">{{ $page->title }}</h4>
                    <p class="text-muted mb-4">{{ Str::limit(strip_tags($page->content), 120) }}</p>
                    <a href="{{ route('pages.show', $page->slug) }}" class="btn btn-outline-primary">
                        <i class="fas fa-book-open me-2"></i>Read More
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">No information pages available</h3>
                    <p class="text-muted mb-4">Information pages will be added soon by the administrator</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Support Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center p-5">
                    <h3 class="fw-bold mb-3">Need More Help?</h3>
                    <p class="text-muted mb-4">Can't find what you're looking for? Our support team is here to help you.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('contact') }}" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Contact Support
                        </a>
                        <a href="tel:+911234567890" class="btn btn-outline-primary">
                            <i class="fas fa-phone me-2"></i>Call Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.page-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>
@endsection