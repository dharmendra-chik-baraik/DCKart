@extends('admin.layouts.app')

@section('title', 'Product Analytics - Admin Panel')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Product Analytics</h1>
        </div>

        <!-- Performance Metrics -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Performance Metrics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-primary shadow-sm h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Products</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $analyticsData['topProducts']->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-success shadow-sm h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Revenue</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            ₹{{ number_format($analyticsData['topProducts']->sum('total_revenue'), 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-info shadow-sm h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Units Sold</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $analyticsData['topProducts']->sum('total_sold') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-warning shadow-sm h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Avg. Price</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            ₹{{ number_format($analyticsData['topProducts']->sum('total_revenue') / $analyticsData['topProducts']->sum('total_sold'), 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.products') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="period">Time Period</label>
                                <select class="form-control" id="period" name="period">
                                    <option value="7d" {{ $filters['period'] == '7d' ? 'selected' : '' }}>Last 7 Days
                                    </option>
                                    <option value="30d" {{ $filters['period'] == '30d' ? 'selected' : '' }}>Last 30 Days
                                    </option>
                                    <option value="90d" {{ $filters['period'] == '90d' ? 'selected' : '' }}>Last 90 Days
                                    </option>
                                    <option value="1y" {{ $filters['period'] == '1y' ? 'selected' : '' }}>Last Year
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="limit">Top Products Limit</label>
                                <select class="form-control" id="limit" name="limit">
                                    <option value="5" {{ $filters['limit'] == 5 ? 'selected' : '' }}>Top 5</option>
                                    <option value="10" {{ $filters['limit'] == 10 ? 'selected' : '' }}>Top 10</option>
                                    <option value="20" {{ $filters['limit'] == 20 ? 'selected' : '' }}>Top 20</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    <a href="{{ route('admin.reports.products') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Top Categories -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top Categories by Revenue</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="categoryRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top Categories by Quantity</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="categoryQuantityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Top Performing Products</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>SKU</th>
                                            <th>Units Sold</th>
                                            <th>Revenue</th>
                                            <th>Avg. Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($analyticsData['topProducts'] as $index => $product)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->sku }}</td>
                                                <td>{{ $product->total_sold }}</td>
                                                <td>₹{{ number_format($product->total_revenue, 2) }}</td>
                                                <td>₹{{ number_format($product->total_revenue / $product->total_sold, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Trend -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Revenue Trend</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="revenueTrendChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-primary"></i> Revenue
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-success"></i> Orders
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Trend Chart
        const trendData = @json($analyticsData['revenueTrend']);
        const trendDates = trendData.map(item => item.date);
        const trendRevenue = trendData.map(item => parseFloat(item.revenue));
        const trendOrders = trendData.map(item => parseInt(item.orders));

        const trendCtx = document.getElementById('revenueTrendChart').getContext('2d');
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendDates,
                datasets: [{
                    label: 'Revenue',
                    data: trendRevenue,
                    borderColor: 'rgb(78, 115, 223)',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Orders',
                    data: trendOrders,
                    borderColor: 'rgb(28, 200, 138)',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Category Revenue Chart
        const topCategories = @json($analyticsData['topCategories']->take(10));
        const categoryNames = topCategories.map(item => item.name);
        const categoryRevenue = topCategories.map(item => parseFloat(item.total_revenue));

        const categoryRevCtx = document.getElementById('categoryRevenueChart').getContext('2d');
        const categoryRevChart = new Chart(categoryRevCtx, {
            type: 'bar',
            data: {
                labels: categoryNames,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: categoryRevenue,
                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Revenue (₹)'
                        }
                    }
                }
            }
        });

        // Category Quantity Chart
        const categoryQuantity = topCategories.map(item => parseInt(item.total_quantity));

        const categoryQtyCtx = document.getElementById('categoryQuantityChart').getContext('2d');
        const categoryQtyChart = new Chart(categoryQtyCtx, {
            type: 'bar',
            data: {
                labels: categoryNames,
                datasets: [{
                    label: 'Quantity Sold',
                    data: categoryQuantity,
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity Sold'
                        }
                    }
                }
            }
        });
    </script>
@endpush
