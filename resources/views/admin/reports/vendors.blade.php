@extends('admin.layouts.app')

@section('title', 'Vendor Performance - Admin Panel')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Vendor Performance</h1>
        </div>

        <!-- Performance Metrics -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card border-left-primary shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Vendors</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $performanceData['salesByVendor']->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-store fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-left-success shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Vendor Revenue</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ₹{{ number_format($performanceData['salesByVendor']->sum('total_sales'), 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-left-info shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Avg. Vendor Revenue</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ₹{{ number_format($performanceData['salesByVendor']->avg('total_sales'), 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-left-warning shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Top Vendor Share</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $topVendorRevenue = $performanceData['topVendors']->first()->total_revenue ?? 0;
                                        $totalRevenue = $performanceData['salesByVendor']->sum('total_sales');
                                        $share = $totalRevenue > 0 ? ($topVendorRevenue / $totalRevenue) * 100 : 0;
                                    @endphp
                                    {{ number_format($share, 1) }}%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-crown fa-2x text-gray-300"></i>
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
                <form method="GET" action="{{ route('admin.reports.vendors') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ $filters['start_date'] }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ $filters['end_date'] }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="period">Time Period</label>
                                <select class="form-control" id="period" name="period">
                                    <option value="7d" {{ $filters['period'] == '7d' ? 'selected' : '' }}>Last 7 Days
                                    </option>
                                    <option value="30d" {{ $filters['period'] == '30d' ? 'selected' : '' }}>Last 30 Days
                                    </option>
                                    <option value="90d" {{ $filters['period'] == '90d' ? 'selected' : '' }}>Last 90 Days
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    <a href="{{ route('admin.reports.vendors') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Top Vendors -->
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top Performing Vendors</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Vendor</th>
                                        <th>Total Revenue</th>
                                        <th>Total Orders</th>
                                        <th>Avg. Order Value</th>
                                        <th>Performance Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($performanceData['topVendors'] as $index => $vendor)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $vendor->shop_name }}</td>
                                            <td>₹{{ number_format($vendor->total_revenue, 2) }}</td>
                                            <td>{{ $vendor->total_orders }}</td>
                                            <td>₹{{ number_format($vendor->total_revenue / $vendor->total_orders, 2) }}
                                            </td>
                                            <td>
                                                @php
                                                    $score = min(
                                                        100,
                                                        ($vendor->total_revenue /
                                                            max(
                                                                $performanceData['topVendors']->max('total_revenue'),
                                                                1,
                                                            )) *
                                                            100,
                                                    );
                                                @endphp
                                                <div class="progress">
                                                    <div class="progress-bar 
                                                @if ($score >= 80) bg-success
                                                @elseif($score >= 60) bg-info
                                                @elseif($score >= 40) bg-warning
                                                @else bg-danger @endif"
                                                        role="progressbar" style="width: {{ $score }}%">
                                                        {{ number_format($score, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vendor Distribution -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Revenue Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="vendorDistributionChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            @foreach ($performanceData['topVendors']->take(5) as $index => $vendor)
                                <span class="mr-2">
                                    <i
                                        class="fas fa-circle text-{{ ['primary', 'success', 'info', 'warning', 'danger'][$index] }}"></i>
                                    {{ Str::limit($vendor->shop_name, 15) }}
                                </span>
                                <br>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales by Vendor Detailed -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Detailed Vendor Performance</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Vendor</th>
                                        <th>Total Sales</th>
                                        <th>Total Orders</th>
                                        <th>Avg. Sale Value</th>
                                        <th>Order Completion Rate</th>
                                        <th>Customer Rating</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($performanceData['salesByVendor'] as $vendor)
                                        <tr>
                                            <td>
                                                <strong>{{ $vendor->shop_name }}</strong>
                                            </td>
                                            <td>₹{{ number_format($vendor->total_sales, 2) }}</td>
                                            <td>{{ $vendor->total_orders }}</td>
                                            <td>₹{{ number_format($vendor->avg_sale_value, 2) }}</td>
                                            <td>
                                                @php
                                                    $completionRate = min(
                                                        100,
                                                        ($vendor->total_orders /
                                                            max(
                                                                $performanceData['salesByVendor']->sum('total_orders'),
                                                                1,
                                                            )) *
                                                            100,
                                                    );
                                                @endphp
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: {{ $completionRate }}%">
                                                        {{ number_format($completionRate, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $rating = rand(35, 50) / 10; // Mock rating - replace with actual data
                                                @endphp
                                                <div class="text-warning">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($rating))
                                                            <i class="fas fa-star"></i>
                                                        @elseif($i - 0.5 <= $rating)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <small class="text-muted">({{ number_format($rating, 1) }})</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">Active</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        // Vendor Distribution Chart
        const topVendors = @json($performanceData['topVendors']->take(5));
        const vendorNames = topVendors.map(item => item.shop_name);
        const vendorRevenue = topVendors.map(item => parseFloat(item.total_revenue));

        const vendorCtx = document.getElementById('vendorDistributionChart').getContext('2d');
        const vendorChart = new Chart(vendorCtx, {
            type: 'doughnut',
            data: {
                labels: vendorNames,
                datasets: [{
                    data: vendorRevenue,
                    backgroundColor: [
                        'rgba(78, 115, 223, 0.8)',
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(54, 185, 204, 0.8)',
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(231, 74, 59, 0.8)'
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(246, 194, 62, 1)',
                        'rgba(231, 74, 59, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
