<!-- resources/views/admin/products/index.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products Management</h1>
        <div class="btn-group">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control" placeholder="Product name, SKU, or description...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Vendor</label>
                    <select name="vendor_id" class="form-control">
                        <option value="">All Vendors</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->shop_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Featured</label>
                    <select name="featured" class="form-control">
                        <option value="">All</option>
                        <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Featured</option>
                        <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>Not Featured</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow-sm">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
            <span class="badge bg-primary">Total: {{ $products->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Vendor</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration + ($products->perPage() * ($products->currentPage() - 1)) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->images->count() > 0)
                                    <img src="{{ $product->images->first()->image_path }}" 
                                         alt="{{ $product->name }}" 
                                         class="rounded me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                    <div class="rounded bg-light text-center me-3 d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">SKU: {{ $product->sku }}</small>
                                        <br>
                                        <small class="text-muted">Slug: {{ $product->slug }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 30px; height: 30px; font-size: 12px;">
                                        {{ strtoupper(substr($product->vendor->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <small class="d-block">{{ $product->vendor->shop_name }}</small>
                                        <small class="text-muted">{{ $product->vendor->user->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $product->category->name }}</span>
                                @if($product->categories->count() > 1)
                                <br><small class="text-muted">+{{ $product->categories->count() - 1 }} more</small>
                                @endif
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    <strong class="text-success">${{ number_format($product->final_price, 2) }}</strong>
                                    @if($product->sale_price)
                                    <br>
                                    <small class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</small>
                                    <span class="badge bg-danger ms-1">{{ $product->discount_percentage }}% OFF</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="badge {{ $product->stock_status == 'in_stock' ? 'bg-success' : ($product->stock_status == 'out_of_stock' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $product->stock }}
                                    </span>
                                    <br>
                                    <small class="text-muted text-capitalize">{{ str_replace('_', ' ', $product->stock_status) }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if($product->is_featured)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star me-1"></i>Featured
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Regular</span>
                                @endif
                            </td>
                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.products.show', $product->id) }}" 
                                       class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Actions Dropdown -->
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <!-- Status Actions -->
                                            @if($product->status)
                                            <li>
                                                <form action="{{ route('admin.products.change-status', $product->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="0">
                                                    <button type="submit" class="dropdown-item text-warning">
                                                        <i class="fas fa-pause me-2"></i>Deactivate
                                                    </button>
                                                </form>
                                            </li>
                                            @else
                                            <li>
                                                <form action="{{ route('admin.products.change-status', $product->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="1">
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-play me-2"></i>Activate
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            
                                            <!-- Featured Toggle -->
                                            <li>
                                                <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item {{ $product->is_featured ? 'text-secondary' : 'text-warning' }}">
                                                        <i class="fas {{ $product->is_featured ? 'fa-star' : 'fa-star' }} me-2"></i>
                                                        {{ $product->is_featured ? 'Remove Featured' : 'Mark Featured' }}
                                                    </button>
                                                </form>
                                            </li>

                                            <!-- Stock Update -->
                                            <li>
                                                <a href="#" class="dropdown-item text-info" 
                                                   data-bs-toggle="modal" data-bs-target="#stockModal{{ $product->id }}">
                                                    <i class="fas fa-boxes me-2"></i>Update Stock
                                                </a>
                                            </li>
                                            
                                            <li><hr class="dropdown-divider"></li>
                                            
                                            <!-- Delete -->
                                            <li>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
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

                                <!-- Stock Update Modal -->
                                <div class="modal fade" id="stockModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.products.update-stock', $product->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Stock: {{ $product->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="stock{{ $product->id }}" class="form-label">Stock Quantity</label>
                                                        <input type="number" class="form-control" 
                                                               id="stock{{ $product->id }}" name="stock" 
                                                               value="{{ $product->stock }}" min="0" required>
                                                    </div>
                                                    <div class="form-text">
                                                        Current stock status: 
                                                        <span class="badge {{ $product->stock_status == 'in_stock' ? 'bg-success' : ($product->stock_status == 'out_of_stock' ? 'bg-danger' : 'bg-warning') }}">
                                                            {{ str_replace('_', ' ', $product->stock_status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Stock</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No products found</h5>
                                <p class="text-muted">Try adjusting your filters or add a new product.</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Add First Product
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="d-flex custom-pagination justify-content-between align-items-center mt-4">
                <nav>
                    {{ $products->links('pagination::bootstrap-5') }}
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .table th,
        .table td {
            vertical-align: middle;
        }
        .custom-pagination nav{
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
    </style>
@endpush