@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Inventory Management</h1>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-3">
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-boxes widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Total Products</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['total_products'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-check-circle widget-icon text-success"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">In Stock</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['in_stock'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-exclamation-triangle widget-icon text-warning"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Low Stock</h5>
                        <h3 class="mt-3 mb-3">
                            <a href="{{ route('admin.inventory.low-stock') }}" class="text-warning">
                                {{ $stats['low_stock'] }}
                            </a>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-times-circle widget-icon text-danger"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Out of Stock</h5>
                        <h3 class="mt-3 mb-3">
                            <a href="{{ route('admin.inventory.out-of-stock') }}" class="text-danger">
                                {{ $stats['out_of_stock'] }}
                            </a>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <form method="GET" class="d-flex">
                                    <input type="text" name="search" class="form-control me-2"
                                        placeholder="Search products..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" onchange="window.location.href=this.value">
                                    <option value="{{ route('admin.inventory.index') }}">All Stock Status</option>
                                    <option value="{{ route('admin.inventory.index', ['stock_status' => 'in_stock']) }}"
                                        {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>
                                        In Stock
                                    </option>
                                    <option
                                        value="{{ route('admin.inventory.index', ['stock_status' => 'out_of_stock']) }}"
                                        {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>
                                        Out of Stock
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-5 text-end">
                                <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Low Stock ({{ $stats['low_stock'] }})
                                </a>
                                <a href="{{ route('admin.inventory.out-of-stock') }}" class="btn btn-danger">
                                    <i class="fas fa-times-circle"></i> Out of Stock ({{ $stats['out_of_stock'] }})
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Vendor</th>
                                        <th>Current Stock</th>
                                        <th>Stock Status</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($product->images->first())
                                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                                            alt="{{ $product->name }}" class="rounded me-3" width="40">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="fas fa-box text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                                        <small class="text-muted">{{ $product->category->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->sku }}</td>
                                            <td>{{ $product->vendor->shop_name }}</td>
                                            <td>
                                                <span
                                                    class="fw-bold @if ($product->stock < 10) text-danger @elseif($product->stock < 20) text-warning @else text-success @endif">
                                                    {{ $product->stock }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge 
                                            @if ($product->stock_status == 'in_stock') bg-success
                                            @elseif($product->stock_status == 'out_of_stock') bg-danger
                                            @else bg-warning @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                                                </span>
                                            </td>
                                            <td>₹{{ number_format($product->price, 2) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editStockModal" data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-current-stock="{{ $product->stock }}">
                                                    <i class="fas fa-edit"></i> Update Stock
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">No products found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Stock Modal -->
    <div class="modal fade" id="editStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStockForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>Product: <strong id="modalProductName"></strong></p>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0"
                                required>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editStockModal = document.getElementById('editStockModal');

            editStockModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-product-id');
                const productName = button.getAttribute('data-product-name');
                const currentStock = button.getAttribute('data-current-stock');

                document.getElementById('modalProductName').textContent = productName;
                document.getElementById('stock').value = currentStock;

                base_url = '{{ url('/') }}';
                const form = document.getElementById('updateStockForm');
                form.action = base_url + `/admin/inventory/${productId}`;
            });
        });
    </script>
@endpush
