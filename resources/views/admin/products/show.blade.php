<!-- resources/views/admin/products/show.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Product Details: ' . $product->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product Details</h1>
        <div class="btn-group">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Product Information -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
                </div>
                <div class="card-body text-center">
                    @if($product->images->count() > 0)
                    <img src="{{ $product->images->first()->image_path }}" 
                         alt="{{ $product->name }}" 
                         class="rounded mb-3" 
                         style="width: 100%; max-height: 200px; object-fit: cover;">
                    @else
                    <div class="rounded bg-light text-center mb-3 d-flex align-items-center justify-content-center"
                         style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                    @endif
                    
                    <h4>{{ $product->name }}</h4>
                    <p class="text-muted">SKU: {{ $product->sku }}</p>
                    
                    <div class="row text-start mt-4">
                        <div class="col-6">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Featured:</strong>
                        </div>
                        <div class="col-6">
                            @if($product->is_featured)
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>Featured
                                </span>
                            @else
                                <span class="badge bg-secondary">Regular</span>
                            @endif
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Stock:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge {{ $product->stock_status == 'in_stock' ? 'bg-success' : ($product->stock_status == 'out_of_stock' ? 'bg-danger' : 'bg-warning') }}">
                                {{ $product->stock }}
                            </span>
                        </div>
                    </div>

                    <div class="row text-start mt-2">
                        <div class="col-6">
                            <strong>Created:</strong>
                        </div>
                        <div class="col-6">
                            {{ $product->created_at->format('M d, Y') }}
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
                        @if($product->status)
                        <form action="{{ route('admin.products.change-status', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="0">
                            <button type="submit" class="btn btn-warning w-100 mb-2">
                                <i class="fas fa-pause me-2"></i>Deactivate Product
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.products.change-status', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-play me-2"></i>Activate Product
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-{{ $product->is_featured ? 'secondary' : 'warning' }} w-100 mb-2">
                                <i class="fas {{ $product->is_featured ? 'fa-star' : 'fa-star' }} me-2"></i>
                                {{ $product->is_featured ? 'Remove Featured' : 'Mark Featured' }}
                            </button>
                        </form>

                        <!-- Stock Update Form -->
                        <form action="{{ route('admin.products.update-stock', $product->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="number" class="form-control" name="stock" value="{{ $product->stock }}" min="0" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details & Stats -->
        <div class="col-md-8">
            <!-- Product Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Pricing Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Regular Price:</strong></td>
                                    <td class="text-end">${{ number_format($product->price, 2) }}</td>
                                </tr>
                                @if($product->sale_price)
                                <tr>
                                    <td><strong>Sale Price:</strong></td>
                                    <td class="text-end text-success">${{ number_format($product->sale_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td class="text-end text-danger">{{ $product->discount_percentage }}% OFF</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Final Price:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($product->final_price, 2) }}</strong></td>
                                </tr>
                            </table>

                            <h5 class="mt-4">Inventory</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Stock Quantity:</strong></td>
                                    <td class="text-end">{{ $product->stock }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Stock Status:</strong></td>
                                    <td class="text-end text-capitalize">{{ str_replace('_', ' ', $product->stock_status) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Vendor & Categories</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Vendor:</strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-warning">{{ $product->vendor->shop_name }}</span>
                                        <br><small class="text-muted">{{ $product->vendor->user->name }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Main Category:</strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-info">{{ $product->category->name }}</span>
                                    </td>
                                </tr>
                                @if($product->categories->count() > 1)
                                <tr>
                                    <td><strong>Additional Categories:</strong></td>
                                    <td class="text-end">
                                        @foreach($product->categories->where('id', '!=', $product->category_id) as $category)
                                        <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                            </table>

                            @if($product->weight || $product->length)
                            <h5 class="mt-4">Dimensions</h5>
                            <table class="table table-sm">
                                @if($product->weight)
                                <tr>
                                    <td><strong>Weight:</strong></td>
                                    <td class="text-end">{{ $product->weight }} kg</td>
                                </tr>
                                @endif
                                @if($product->length && $product->width && $product->height)
                                <tr>
                                    <td><strong>Dimensions:</strong></td>
                                    <td class="text-end">{{ $product->length }} × {{ $product->width }} × {{ $product->height }} cm</td>
                                </tr>
                                @endif
                            </table>
                            @endif
                        </div>
                    </div>

                    @if($product->short_description || $product->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            @if($product->short_description)
                            <h5>Short Description</h5>
                            <p class="text-muted">{{ $product->short_description }}</p>
                            @endif

                            @if($product->description)
                            <h5>Full Description</h5>
                            <div class="text-muted">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Stats -->
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary">{{ $stats['total_orders'] ?? 0 }}</h4>
                                <small class="text-muted">Total Orders</small>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h4>
                                <small class="text-muted">Total Revenue</small>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info">{{ $stats['total_reviews'] ?? 0 }}</h4>
                                <small class="text-muted">Total Reviews</small>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning">{{ number_format($stats['average_rating'] ?? 0, 1) }}/5</h4>
                                <small class="text-muted">Avg Rating</small>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-danger">{{ $stats['total_wishlists'] ?? 0 }}</h4>
                                <small class="text-muted">Wishlists</small>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-secondary">{{ $stats['total_carts'] ?? 0 }}</h4>
                                <small class="text-muted">In Carts</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            @if($product->images->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Product Images</h6>
                    <span class="badge bg-primary">{{ $product->images->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($product->images as $image)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <img src="{{ $image->image_path }}" 
                                     alt="Product Image" 
                                     class="card-img-top" 
                                     style="height: 150px; object-fit: cover;">
                                <div class="card-body text-center">
                                    <small class="text-muted">Position: {{ $image->position }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Product Variants -->
            @if($product->variants->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Product Variants</h6>
                    <span class="badge bg-warning">{{ $product->variants->count() }}</span>
                </div>
                <div class="card-body">
                    @foreach($product->variants as $variant)
                    <div class="mb-3">
                        <h6>{{ $variant->name }}</h6>
                        <div class="row">
                            @foreach($variant->values as $value)
                            <div class="col-md-4 mb-2">
                                <div class="border rounded p-2">
                                    <strong>{{ $value->value }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Price: +${{ number_format($value->price_adjustment, 2) }}
                                        @if($value->stock !== null)
                                        | Stock: {{ $value->stock }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection