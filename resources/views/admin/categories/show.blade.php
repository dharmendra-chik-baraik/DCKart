<!-- resources/views/admin/categories/show.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Category Details: ' . $category->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Category Details</h1>
        <div class="btn-group">
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Information -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
                </div>
                <div class="card-body text-center">
                    @if($category->icon)
                    <div class="text-primary mb-3" style="font-size: 3rem;">
                        <i class="{{ $category->icon }}"></i>
                    </div>
                    @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; font-size: 24px;">
                        {{ strtoupper(substr($category->name, 0, 2)) }}
                    </div>
                    @endif
                    
                    <h4>{{ $category->name }}</h4>
                    <p class="text-muted">{{ $category->slug }}</p>
                    
                    <div class="row text-start mt-4">
                        <div class="col-6">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge {{ $category->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $category->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Parent:</strong>
                        </div>
                        <div class="col-6">
                            @if($category->parent)
                                <span class="badge bg-info">{{ $category->parent->name }}</span>
                            @else
                                <span class="badge bg-success">Main Category</span>
                            @endif
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Created:</strong>
                        </div>
                        <div class="col-6">
                            {{ $category->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($category->status)
                        <form action="{{ route('admin.categories.change-status', $category->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="0">
                            <button type="submit" class="btn btn-warning w-100 mb-2">
                                <i class="fas fa-pause me-2"></i>Deactivate Category
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.categories.change-status', $category->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-play me-2"></i>Activate Category
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->id }}" 
                           class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus me-2"></i>Add Subcategory
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Details & Stats -->
        <div class="col-md-8">
            <!-- Category Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($category->description)
                            <p><strong>Description:</strong></p>
                            <p class="text-muted">{{ $category->description }}</p>
                            @endif

                            @if($category->image)
                            <p><strong>Image:</strong></p>
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" 
                                 class="img-thumbnail" style="max-height: 200px;">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Full URL:</strong></p>
                            <code>{{ url('/categories/' . $category->slug) }}</code>
                            
                            <p class="mt-3"><strong>Hierarchy:</strong></p>
                            <ul class="list-unstyled">
                                @if($category->parent)
                                <li>↳ Parent: {{ $category->parent->name }}</li>
                                @endif
                                <li>↳ Current: <strong>{{ $category->name }}</strong></li>
                                @if($category->children->count() > 0)
                                <li>↳ Children: {{ $category->children->count() }} subcategories</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Stats -->
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary">{{ $stats['total_products'] ?? 0 }}</h4>
                                <small class="text-muted">Total Products</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success">{{ $stats['total_subcategories'] ?? 0 }}</h4>
                                <small class="text-muted">Direct Subcategories</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info">{{ $stats['total_descendants'] ?? 0 }}</h4>
                                <small class="text-muted">Total Descendants</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subcategories -->
            @if($category->children->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Subcategories</h6>
                    <span class="badge bg-warning">{{ $category->children->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($category->children as $child)
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $child->name }}</h6>
                                            <small class="text-muted">{{ $child->products_count ?? 0 }} products</small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.categories.show', $child->id) }}" 
                                               class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $child->id) }}" 
                                               class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection