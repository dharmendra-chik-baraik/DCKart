<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <i class="fas fa-store me-2"></i>
            {{ config('app.name', 'MultiVendor') }}
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Left Navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>

                <!-- Dynamic Categories Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Categories
                    </a>
                    <ul class="dropdown-menu">
                        @php
                            $categories = App\Models\Category::where('status', true)
                                ->whereNull('parent_id')
                                ->with('children')
                                ->take(6)
                                ->get();
                        @endphp

                        @forelse($categories as $category)
                            <li>
                                <a class="dropdown-item" href="{{ route('categories.show', $category->slug) }}">
                                    @if ($category->icon)
                                        <i class="{{ $category->icon }} me-2"></i>
                                    @else
                                        <i class="fas fa-tag me-2"></i>
                                    @endif
                                    {{ $category->name }}
                                </a>
                            </li>
                        @empty
                            <li><a class="dropdown-item" href="#"><i class="fas fa-tags me-2"></i>All Categories</a></li>
                        @endforelse

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('categories.index') }}"><i
                                    class="fas fa-th-large me-2"></i>All Categories</a></li>
                    </ul>
                </li>

                <!-- Vendors Link -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('vendors.index') }}">Vendors</a>
                </li>

                <!-- Dynamic Pages Dropdown -->
                @php
                    $activePages = App\Models\Page::where('status', true)->get();
                @endphp

                @if ($activePages->count() > 0)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Information
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($activePages as $page)
                                <li>
                                    <a class="dropdown-item" href="{{ route('pages.show', $page->slug) }}">
                                        <i class="fas fa-file-alt me-2"></i>
                                        {{ $page->title }}
                                    </a>
                                </li>
                            @endforeach
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('pages.index') }}">
                                    <i class="fas fa-list me-2"></i> All Pages
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Static Links -->
                <li class="nav-item">
                    <a class="nav-link" href="#">Deals</a>
                </li>

                <!-- Contact Link -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>

            <!-- Search Bar -->
            <form class="d-flex me-3" action="{{ route('search') }}" method="GET" role="search">
                <div class="input-group">
                    <input type="search" name="q" class="form-control" placeholder="Search products..."
                        value="{{ request('q') }}" aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- Right Navigation -->
            <ul class="navbar-nav">
                @auth
                    <!-- User is logged in -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (Auth::user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'vendor')
                                <li>
                                    <a class="dropdown-item" href="{{ route('vendor.dashboard') }}">
                                        <i class="fas fa-store me-2"></i>Vendor Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('vendor.products.index') }}">
                                        <i class="fas fa-box me-2"></i>My Products
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('vendor.orders.index') }}">
                                        <i class="fas fa-shopping-cart me-2"></i>Orders
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.profile.edit') }}">
                                        <i class="fas fa-user me-2"></i>My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.orders.index') }}">
                                        <i class="fas fa-shopping-bag me-2"></i>My Orders
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.wishlist.index') }}">
                                        <i class="fas fa-heart me-2"></i>Wishlist
                                    </a>
                                </li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                    <!-- Cart -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                $cartCount = App\Models\Cart::where('user_id', Auth::id())->count();
                            @endphp
                            @if ($cartCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <!-- Notifications -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            @php
                                // Use Laravel's built-in notifications system
                                $notificationCount = auth()->user()->unreadNotifications()->count();
                            @endphp
                            @if ($notificationCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                    {{ $notificationCount }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2" style="width: 300px;">
                            <li class="dropdown-header">Notifications</li>
                            
                            @php
                                $notifications = auth()->user()->unreadNotifications()->take(5)->get();
                            @endphp
                            
                            @forelse($notifications as $notification)
                                <li>
                                    <a class="dropdown-item small" href="#">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bell text-primary me-2"></i>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                                <div class="text-muted">{{ Str::limit($notification->data['message'] ?? '', 30) }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li><a class="dropdown-item text-muted text-center" href="#">No new notifications</a></li>
                            @endforelse

                            @if($notifications->count() > 0)
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-center small" href="{{ route('customer.notifications.index') }}">
                                        View All Notifications
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-center small text-primary" href="#" 
                                       onclick="event.preventDefault(); document.getElementById('mark-all-read').submit();">
                                        Mark All as Read
                                    </a>
                                    <form id="mark-all-read" action="{{ route('customer.notifications.markAllAsRead') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @endif
                        </ul>
                    </li>
                @else
                    <!-- Guest User -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}" id="cartIcon">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                $cartCount = app(App\Services\Customer\CartService::class)->getCartCount();
                            @endphp
                            @if ($cartCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    id="cartCount">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.wishlist.index') }}">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>