@extends('admin.layouts.app')

@section('title', 'Out of Stock Products')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center mb-4">
                    <h1 class="page-title h3 mb-0 text-gray-800">Out of Stock Products</h1>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
                            <li class="breadcrumb-item active">Out of Stock</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Section -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle me-2"></i>
                    <strong>Alert:</strong> The following products are currently out of stock (0 units available).
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-3">
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
                            <i class="fas fa-exclamation-triangle widget-icon text-warning"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Low Stock</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['low_stock'] }}</h3>
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
                                <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-warning">
                                    <i class="fas fa-exclamation-triangle"></i> View Low Stock
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#bulkRestockModal">
                                    <i class="fas fa-boxes"></i> Bulk Restock
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out of Stock Products Table -->
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
                                            <th>Last Sold</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr class="table-danger">
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
                                                    <span class="fw-bold text-danger">0</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        Out of Stock
                                                    </span>
                                                </td>
                                                <td>₹{{ number_format($product->price, 2) }}</td>
                                                <td>
                                                    @php
                                                        $lastOrder = $product->orderItems()->latest()->first();
                                                    @endphp
                                                    @if ($lastOrder)
                                                        {{ $lastOrder->created_at->format('M d, Y') }}
                                                    @else
                                                        <span class="text-muted">Never</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal" data-bs-target="#restockModal"
                                                        data-product-id="{{ $product->id }}"
                                                        data-product-name="{{ $product->name }}"
                                                        data-product-sku="{{ $product->sku }}">
                                                        <i class="fas fa-boxes"></i> Restock
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
                                <h4 class="text-success">No Out of Stock Products</h4>
                                <p class="text-muted">All products are in stock. Great job!</p>
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

    <!-- Restock Modal -->
    <div class="modal fade" id="restockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Restock Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="restockForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> This product is currently out of stock.
                        </div>
                        <p><strong>Product:</strong> <span id="modalProductName"></span></p>
                        <p><strong>SKU:</strong> <span id="modalProductSku"></span></p>

                        <div class="mb-3">
                            <label for="restockQuantity" class="form-label">Restock Quantity</label>
                            <input type="number" class="form-control" id="restockQuantity" name="stock"
                                min="1" value="10" required>
                            <div class="form-text">Enter the quantity to add to inventory</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Recommended Quantities</label>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="setQuantity(10)">10 units</button>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="setQuantity(25)">25 units</button>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="setQuantity(50)">50 units</button>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="setQuantity(100)">100 units</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Restock Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Restock Modal -->
    <div class="modal fade" id="bulkRestockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Restock - Out of Stock Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.inventory.bulk-update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Restock multiple out of stock items at once. All items will
                            be set to the same quantity.
                        </div>

                        <div class="mb-3">
                            <label for="bulkQuantity" class="form-label">Restock Quantity for All Items</label>
                            <input type="number" class="form-control" id="bulkQuantity" name="bulk_quantity"
                                min="1" value="10" required>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Vendor</th>
                                        <th>New Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                            </td>
                                            <td>{{ $product->sku }}</td>
                                            <td>{{ $product->vendor->shop_name }}</td>
                                            <td>
                                                <input type="hidden" name="products[{{ $loop->index }}][id]"
                                                    value="{{ $product->id }}">
                                                <span class="badge bg-success"
                                                    id="stockPreview{{ $loop->index }}">10</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Restock All Items</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const restockModal = document.getElementById('restockModal');

            restockModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-product-id');
                const productName = button.getAttribute('data-product-name');
                const productSku = button.getAttribute('data-product-sku');

                document.getElementById('modalProductName').textContent = productName;
                document.getElementById('modalProductSku').textContent = productSku;
                base_url = '{{ url('/') }}';
                const form = document.getElementById('restockForm');
                form.action = base_url + `/admin/inventory/${productId}`;
            });

            // Bulk restock quantity preview
            const bulkQuantity = document.getElementById('bulkQuantity');
            if (bulkQuantity) {
                bulkQuantity.addEventListener('input', function() {
                    const quantity = this.value;
                    @foreach ($products as $index => $product)
                        const preview{{ $index }} = document.getElementById(
                            'stockPreview{{ $index }}');
                        if (preview{{ $index }}) {
                            preview{{ $index }}.textContent = quantity;
                        }
                    @endforeach
                });
            }
        });

        function setQuantity(quantity) {
            document.getElementById('restockQuantity').value = quantity;
        }
    </script>
@endpush
