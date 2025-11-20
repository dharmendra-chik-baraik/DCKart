<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MultiVendor')) - Vendor</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome@6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --vendor-primary: #10b981;
            --vendor-secondary: #059669;
            --vendor-success: #28a745;
            --vendor-warning: #f59e0b;
            --vendor-danger: #ef4444;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .vendor-sidebar {
            background: linear-gradient(135deg, var(--vendor-primary) 0%, var(--vendor-secondary) 100%);
            min-height: calc(100vh - 56px);
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        .vendor-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            transition: all 0.3s;
        }
        
        .vendor-sidebar .nav-link:hover, 
        .vendor-sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        
        .vendor-sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        
        .vendor-header {
            background: linear-gradient(to right, var(--vendor-primary), var(--vendor-secondary));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .vendor-content {
            padding: 20px;
            min-height: calc(100vh - 56px);
        }
        
        .vendor-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .vendor-card:hover {
            transform: translateY(-5px);
        }
        
        .vendor-stats-card {
            border-left: 4px solid;
        }
        
        .vendor-stats-card.primary {
            border-left-color: var(--vendor-primary);
        }
        
        .vendor-stats-card.success {
            border-left-color: var(--vendor-success);
        }
        
        .vendor-stats-card.warning {
            border-left-color: var(--vendor-warning);
        }
        
        .vendor-stats-card.danger {
            border-left-color: var(--vendor-danger);
        }
        
        .badge-vendor {
            background-color: var(--vendor-primary);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        @include('vendors.layouts.header')

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                @include('vendors.layouts.sidebar')

                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 vendor-content">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Page header -->
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <div>
                            <h1 class="h2">@yield('page-title', 'Vendor Dashboard')</h1>
                            @hasSection('store-name')
                                <p class="text-muted mb-0">Store: @yield('store-name')</p>
                            @endif
                        </div>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            @yield('page-actions')
                        </div>
                    </div>

                    <!-- Main content area -->
                    @yield('content')
                </main>
            </div>
        </div>

        @include('vendors.layouts.footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>