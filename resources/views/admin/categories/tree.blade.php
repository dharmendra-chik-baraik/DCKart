<!-- resources/views/admin/categories/tree.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Categories Tree View')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Categories Tree View</h1>
        <div class="btn-group">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i> List View
            </a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>
    </div>

    <!-- Categories Tree -->
    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Categories Hierarchy</h6>
        </div>
        <div class="card-body">
            @if($categories->count() > 0)
                <div class="category-tree">
                    @foreach($categories as $category)
                        @include('admin.categories.tree-item', ['category' => $category, 'level' => 0])
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-folder fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No categories found</h5>
                    <p class="text-muted">Start by creating your first category.</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus"></i> Add First Category
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .category-tree {
            padding-left: 0;
        }
        .tree-item {
            margin-bottom: 10px;
        }
        .tree-children {
            padding-left: 30px;
            border-left: 2px solid #e9ecef;
            margin-left: 15px;
        }
        .category-card {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 15px;
            background: #f8f9fa;
        }
        .level-0 { margin-left: 0; }
        .level-1 { margin-left: 20px; }
        .level-2 { margin-left: 40px; }
        .level-3 { margin-left: 60px; }
    </style>
@endpush