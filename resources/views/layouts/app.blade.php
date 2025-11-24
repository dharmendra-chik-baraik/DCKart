<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MultiVendor'))</title>
    <meta name="description" content="@yield('meta_description', 'Your multi-vendor ecommerce platform')">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #a5b4fc;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
            --border: #e2e8f0;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
            border-color: var(--primary);
        }
        
        .alert {
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 0;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }
        
        .page-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary) !important;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" fill="%234f46e5"><polygon points="0,0 1000,1000 0,1000"></polygon></svg>') no-repeat;
            background-size: cover;
            opacity: 0.1;
        }
        
        .min-vh-75 {
            min-height: 75vh;
        }
        
        .feature-icon {
            transition: transform 0.3s ease;
        }
        
        .card:hover .feature-icon {
            transform: scale(1.1);
        }
        
        .category-card, .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .category-card:hover, .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow) !important;
        }
        
        .stat-item {
            padding: 20px 0;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }
        
        /* Navbar Styles */
        .navbar {
            padding: 1rem 0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary) !important;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow);
            border-radius: 0.75rem;
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary);
            color: white;
        }
        
        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, var(--dark) 0%, #334155 100%) !important;
        }
        
        footer a {
            transition: color 0.3s ease;
        }
        
        footer a:hover {
            color: var(--primary) !important;
            text-decoration: none !important;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-2px);
            color: white !important;
        }
        
        .footer-brand {
            font-size: 1.5rem;
        }
        
        .border-secondary {
            border-color: #4a5f7a !important;
        }
        
        .btn-outline-light:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        /* Button Styles */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            font-weight: 500;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }
        
        .feature-card, .category-card, .product-card {
            overflow: hidden;
            height: 100%;
        }
        
        .feature-card:hover, .category-card:hover, .product-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.75rem;
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }
        
        .product-card img {
            transition: transform 0.5s ease;
        }
        
        .product-card:hover img {
            transform: scale(1.05);
        }
        
        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--dark) 0%, #334155 100%);
            padding: 4rem 0;
        }
        
        /* Newsletter Section */
        .newsletter-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 4rem 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .display-4 {
                font-size: 2rem;
            }
            
            .hero-section {
                text-align: center;
                padding: 3rem 0;
            }
            
            .navbar-nav .nav-item {
                margin: 0.2rem 0;
            }
            
            .input-group {
                margin: 1rem 0;
            }
            
            .navbar-nav .btn {
                margin: 0.2rem 0;
                width: 100%;
                text-align: center;
            }
            
            .footer-links {
                text-align: left !important;
                margin-top: 1rem;
            }
            
            .footer-links a {
                display: block;
                margin: 0.5rem 0;
            }
            
            .social-links {
                text-align: center;
            }
            
            .btn-outline-light {
                width: 100%;
                justify-content: center;
                margin-bottom: 0.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .footer-links a {
                display: inline-block;
                margin: 0.25rem 0.5rem 0.25rem 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        @include('layouts.header')

        <main>
            <!-- Flash Messages -->
            <div class="flash-messages">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="container">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="container">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="container">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Please check the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.isConnected) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>