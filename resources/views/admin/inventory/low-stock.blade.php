@extends('admin.layouts.app')

@section('title', 'Low Stock Products')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0 text-gray-800 page-title">Low Stock Products</h1>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
                            <li class="breadcrumb-item active">Low Stock</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alert Section -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Alert:</strong> The following products are running low on stock (less than 10 units).
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-3">
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-exclamation-triangle widget-icon text-warning"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Low Stock Items</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['low_stock'] }}</h3>
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
                        <h3 class="mt-3 mb-3">{{ $stats['out_of_stock'] }}</h3>
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
                            <i class="fas fa-boxes widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Total Products</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['total_products'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to All Inventory
                                </a>
                                <a href="{{ route('admin.inventory.out-of-stock') }}" class="btn btn-danger">
                                    <i class="fas fa-times-circle"></i> View Out of Stock
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#bulkUpdateModal">
                                    <i class="fas fa-edit"></i> Bulk Update Stock
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Products Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0">
                                    <thead class="table-light">
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
                                        @foreach ($products as $product)
                                            <tr
                                                class="@if ($product->stock < 5) table-danger @else table-warning @endif">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($product->images->first())
                                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                                                alt="{{ $product->name }}" class="rounded me-3"
                                                                width="40">
                                                        @else
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                                style="width: 40px; height: 40px;">
                                                                <i class="fas fa-box text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                                            <small
                                                                class="text-muted">{{ $product->category->name }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $product->sku }}</td>
                                                <td>{{ $product->vendor->shop_name }}</td>
                                                <td>
                                                    <span class="fw-bold text-danger">
                                                        {{ $product->stock }}
                                                        @if ($product->stock < 5)
                                                            <small class="d-block text-danger">Critical!</small>
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning">
                                                        Low Stock
                                                    </span>
                                                </td>
                                                <td>₹{{ number_format($product->price, 2) }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#editStockModal"
                                                        data-product-id="{{ $product->id }}"
                                                        data-product-name="{{ $product->name }}"
                                                        data-current-stock="{{ $product->stock }}">
                                                        <i class="fas fa-edit"></i> Update
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $products->links('pagination::bootstrap-5') }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h4 class="text-success">No Low Stock Products</h4>
                                <p class="text-muted">All products have sufficient stock levels.</p>
                                <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back to Inventory
                                </a>
                            </div>
                        @endif
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
                    <h5 class="modal-title">Update Stock - Low Stock Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStockForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> This product is running low on stock.
                        </div>
                        <p>Product: <strong id="modalProductName"></strong></p>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0"
                                required>
                            <div class="form-text">Recommended minimum: 10 units</div>
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

    <!-- Bulk Update Modal -->
    <div class="modal fade" id="bulkUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Update Stock - Low Stock Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.inventory.bulk-update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Update stock quantities for multiple low stock items at
                            once.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Current Stock</th>
                                        <th>New Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                <br><small class="text-muted">{{ $product->sku }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $product->stock }}</span>
                                            </td>
                                            <td>
                                                <input type="hidden" name="products[{{ $loop->index }}][id]"
                                                    value="{{ $product->id }}">
                                                <input type="number" class="form-control form-control-sm"
                                                    name="products[{{ $loop->index }}][stock]"
                                                    value="{{ $product->stock }}" min="0" required>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update All Stock</button>
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
