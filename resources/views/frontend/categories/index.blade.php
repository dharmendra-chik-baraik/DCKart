@extends('layouts.app')

@section('title', 'Categories - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold text-dark mb-3">Product Categories</h1>
            <p class="text-muted lead">Browse products by category</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($categories as $category)
        <div class="col-lg-4 col-md-6">
            <div class="card category-card border-0 shadow-sm h-100">
                <div class="card-body text-center p-5">
                    <div class="category-icon mb-4">
                        @if($category->icon)
                            <i class="{{ $category->icon }} fa-3x text-primary"></i>
                        @else
                            <i class="fas fa-tag fa-3x text-primary"></i>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-3">{{ $category->name }}</h4>
                    <p class="text-muted mb-4">{{ $category->description }}</p>
                    <div class="mb-3">
                        <span class="badge bg-primary">{{ $category->products_count }} Products</span>
                    </div>
                    <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-primary">
                        Browse Products
                    </a>
                    
                    @if($category->children->count() > 0)
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">Subcategories</h6>
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            @foreach($category->children as $child)
                            <a href="{{ route('categories.show', $child->slug) }}" class="badge bg-light text-dark text-decoration-none">
                                {{ $child->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No categories available</h4>
                <p class="text-muted">Categories will be added soon</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection