<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MultiVendor')) - Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Toastr CSS for better notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<style>
    :root {
        --admin-primary: #2c3e50;
        --admin-secondary: #34495e;
        --admin-border: #e9ecef;
        --admin-bg: #f8f9fa;
        --admin-text: #495057;
        --admin-success: #28a745;
        --admin-warning: #ffc107;
        --admin-danger: #dc3545;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--admin-bg);
        color: var(--admin-text);
        font-size: 0.9rem;
    }

    .admin-sidebar {
        background-color: white;
        min-height: calc(100vh - 60px);
        border-right: 1px solid var(--admin-border);
        padding: 0;
    }

    .admin-sidebar .nav-link {
        color: var(--admin-text);
        padding: 0.75rem 1rem;
        border-radius: 0;
        margin-bottom: 0;
        border-bottom: 1px solid var(--admin-border);
        transition: all 0.2s;
    }

    .admin-sidebar .nav-link:hover,
    .admin-sidebar .nav-link.active {
        color: var(--admin-primary);
        background-color: rgba(44, 62, 80, 0.05);
        border-left: 3px solid var(--admin-primary);
    }

    .admin-sidebar .nav-link i {
        width: 20px;
        margin-right: 10px;
        text-align: center;
    }

    .admin-header {
        background-color: white;
        border-bottom: 1px solid var(--admin-border);
        height: 60px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .admin-content {
        padding: 20px;
        min-height: calc(100vh - 60px);
        background-color: var(--admin-bg);
    }

    .admin-card {
        border: 1px solid var(--admin-border);
        border-radius: 4px;
        background-color: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .admin-card:hover {
        transform: none;
    }

    .stats-card {
        border-left: 4px solid;
        padding: 1rem;
    }

    .stats-card.primary {
        border-left-color: var(--admin-primary);
    }

    .stats-card.success {
        border-left-color: var(--admin-success);
    }

    .stats-card.warning {
        border-left-color: var(--admin-warning);
    }

    .stats-card.danger {
        border-left-color: var(--admin-danger);
    }

    /* Toastr customization */
    .toast {
        border-radius: 4px;
    }
</style>

    @stack('styles')
</head>

<body>
    <div id="app">
        @include('admin.layouts.header')

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                @include('admin.layouts.sidebar')

                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-content">
                    <!-- Main content area -->
                    @yield('content')
                </main>
            </div>
        </div>

        @include('admin.layouts.footer')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr JS for better notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Initialize Toastr
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Show toastr messages from session
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif

            @if (session('warning'))
                toastr.warning('{{ session('warning') }}');
            @endif

            @if (session('info'))
                toastr.info('{{ session('info') }}');
            @endif
        });
    </script>

    @stack('scripts')
</body>

</html>
