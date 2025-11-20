<!-- resources/views/admin/products/edit.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Edit Product: ' . $product->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product: {{ $product->name }}</h1>
        <div class="btn-group">
            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Product Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Basic Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Product Name *</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="slug" class="form-label">Slug *</label>
                                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                                       id="slug" name="slug" value="{{ old('slug', $product->slug) }}" required>
                                                @error('slug')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="sku" class="form-label">SKU *</label>
                                                <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                                       id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                                @error('sku')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="vendor_id" class="form-label">Vendor *</label>
                                                <select class="form-control @error('vendor_id') is-invalid @enderror" 
                                                        id="vendor_id" name="vendor_id" required>
                                                    <option value="">Select Vendor</option>
                                                    @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}" {{ old('vendor_id', $product->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                                        {{ $vendor->shop_name }} ({{ $vendor->user->name }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('vendor_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="short_description" class="form-label">Short Description</label>
                                            <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                                      id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                                            @error('short_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Full Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing & Inventory -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Pricing & Inventory</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="price" class="form-label">Regular Price ($) *</label>
                                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                                       id="price" name="price" value="{{ old('price', $product->price) }}" min="0" required>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="sale_price" class="form-label">Sale Price ($)</label>
                                                <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                                       id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0">
                                                @error('sale_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    @if($product->sale_price && $product->price > 0)
                                                    Current discount: <span class="text-danger">{{ $product->discount_percentage }}% OFF</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="stock" class="form-label">Stock Quantity</label>
                                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                                       id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0">
                                                @error('stock')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="stock_status" class="form-label">Stock Status *</label>
                                                <select class="form-control @error('stock_status') is-invalid @enderror" 
                                                        id="stock_status" name="stock_status" required>
                                                    <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                                    <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                                    <option value="on_backorder" {{ old('stock_status', $product->stock_status) == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                                                </select>
                                                @error('stock_status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Information -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">SEO Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">Meta Title</label>
                                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                                   id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                      id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $product->meta_description) }}</textarea>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                                   id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}">
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="col-md-4">
                                <!-- Categories -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Categories</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Main Category *</label>
                                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                                    id="category_id" name="category_id" required>
                                                <option value="">Select Main Category</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Additional Categories</label>
                                            <div class="category-checkboxes" style="max-height: 200px; overflow-y: auto;">
                                                @foreach($categories as $category)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="categories[]" value="{{ $category->id }}" 
                                                           id="category_{{ $category->id }}"
                                                           {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                            @error('categories')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Status -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Product Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" 
                                                       {{ old('status', $product->status) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status">
                                                    Product Active
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                                       {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured">
                                                    Featured Product
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dimensions -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Dimensions & Weight</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label for="weight" class="form-label">Weight (kg)</label>
                                                <input type="number" step="0.01" class="form-control" 
                                                       id="weight" name="weight" value="{{ old('weight', $product->weight) }}" min="0">
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label for="length" class="form-label">Length (cm)</label>
                                                <input type="number" step="0.01" class="form-control" 
                                                       id="length" name="length" value="{{ old('length', $product->length) }}" min="0">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label for="width" class="form-label">Width (cm)</label>
                                                <input type="number" step="0.01" class="form-control" 
                                                       id="width" name="width" value="{{ old('width', $product->width) }}" min="0">
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label for="height" class="form-label">Height (cm)</label>
                                                <input type="number" step="0.01" class="form-control" 
                                                       id="height" name="height" value="{{ old('height', $product->height) }}" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Images Preview -->
                                @if($product->images->count() > 0)
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Current Images</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($product->images as $image)
                                            <div class="col-6 mb-2">
                                                <img src="{{ $image->image_path }}" 
                                                     alt="Product Image" 
                                                     class="img-thumbnail"
                                                     style="width: 100%; height: 80px; object-fit: cover;">
                                                <small class="d-block text-center text-muted">Pos: {{ $image->position }}</small>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-generate slug from product name
document.getElementById('name').addEventListener('input', function() {
    const slugInput = document.getElementById('slug');
    // Only auto-generate if slug is empty or matches the old product name
    const currentSlug = '{{ $product->slug }}';
    const currentName = '{{ $product->name }}';
    
    if (!slugInput.value || slugInput.value === currentSlug) {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        slugInput.value = slug;
    }
});

// Price validation
document.getElementById('sale_price').addEventListener('input', function() {
    const priceInput = document.getElementById('price');
    const salePrice = parseFloat(this.value);
    const price = parseFloat(priceInput.value);
    
    if (salePrice && price && salePrice >= price) {
        this.setCustomValidity('Sale price must be less than regular price');
    } else {
        this.setCustomValidity('');
    }
});

// Prevent main category from being selected in additional categories
document.addEventListener('DOMContentLoaded', function() {
    const mainCategorySelect = document.getElementById('category_id');
    const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]');
    
    function updateCategoryCheckboxes() {
        const mainCategoryId = mainCategorySelect.value;
        categoryCheckboxes.forEach(checkbox => {
            if (checkbox.value === mainCategoryId) {
                checkbox.disabled = true;
                checkbox.checked = false;
            } else {
                checkbox.disabled = false;
            }
        });
    }
    
    mainCategorySelect.addEventListener('change', updateCategoryCheckboxes);
    updateCategoryCheckboxes(); // Initial call
});
</script>
@endsection