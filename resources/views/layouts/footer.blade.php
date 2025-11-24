<footer class="bg-dark text-white pt-5">
    <div class="container">
        <!-- Main Footer Content -->
        <div class="row g-4">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand mb-3">
                    <h4 class="fw-bold text-primary">
                        <i class="fas fa-store me-2"></i>
                        {{ config('app.name', 'MultiVendor') }}
                    </h4>
                </div>
                <p class="text-light mb-4">
                    Your trusted multi-vendor marketplace connecting buyers with quality sellers. 
                    Discover unique products and support local businesses.
                </p>
                <div class="social-links">
                    <a href="#" class="text-white me-3" title="Facebook">
                        <i class="fab fa-facebook-f fa-lg"></i>
                    </a>
                    <a href="#" class="text-white me-3" title="Twitter">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="text-white me-3" title="Instagram">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="#" class="text-white me-3" title="LinkedIn">
                        <i class="fab fa-linkedin-in fa-lg"></i>
                    </a>
                    <a href="#" class="text-white" title="YouTube">
                        <i class="fab fa-youtube fa-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ url('/') }}" class="text-light text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="{{ route('products.index') }}" class="text-light text-decoration-none">Products</a></li>
                    <li class="mb-2"><a href="{{ route('vendors.index') }}" class="text-light text-decoration-none">Vendors</a></li>
                    <li class="mb-2"><a href="{{ route('categories.index') }}" class="text-light text-decoration-none">Categories</a></li>
                    <li class="mb-2"><a href="{{ route('pages.index') }}" class="text-light text-decoration-none">All Pages</a></li>
                </ul>
            </div>

            <!-- Information Pages -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4">Information</h5>
                <ul class="list-unstyled">
                    @php
                        $infoPages = App\Models\Page::where('status', true)
                            ->orderBy('title')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @forelse($infoPages as $page)
                        <li class="mb-2">
                            <a href="{{ route('pages.show', $page->slug) }}" 
                               class="text-light text-decoration-none">
                                {{ $page->title }}
                            </a>
                        </li>
                    @empty
                        <li class="mb-2">
                            <a href="{{ route('pages.index') }}" class="text-light text-decoration-none">
                                Our Pages
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('contact') }}" class="text-light text-decoration-none">
                                Contact Us
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none">
                                About Us
                            </a>
                        </li>
                    @endforelse
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4">Customer Service</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('contact') }}" class="text-light text-decoration-none">Contact Us</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Help Center</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Returns & Refunds</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Shipping Info</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Track Order</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4">Contact Us</h5>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        <span class="text-light">123 Business St, City, Country</span>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-phone text-primary me-2"></i>
                        <a href="tel:+1234567890" class="text-light text-decoration-none">+1 (234) 567-890</a>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <a href="mailto:info@multivendor.com" class="text-light text-decoration-none">info@multivendor.com</a>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <span class="text-light">Mon-Fri: 9AM-6PM</span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Bottom Footer -->
        <div class="row mt-5 pb-4 pt-4 border-top border-secondary">
            <div class="col-md-6">
                <p class="text-light mb-0">
                    &copy; {{ date('Y') }} {{ config('app.name', 'MultiVendor') }}. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="footer-links">
                    @php
                        $policyPages = App\Models\Page::where('status', true)
                            ->whereIn('slug', ['privacy-policy', 'terms-of-service', 'cookie-policy'])
                            ->get();
                    @endphp
                    
                    @foreach($policyPages as $page)
                        <a href="{{ route('pages.show', $page->slug) }}" 
                           class="text-light text-decoration-none me-3">
                            {{ $page->title }}
                        </a>
                    @endforeach
                    
                    <!-- Fallback if policy pages don't exist -->
                    @if($policyPages->count() === 0)
                        <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-light text-decoration-none me-3">Terms of Service</a>
                        <a href="#" class="text-light text-decoration-none">Cookie Policy</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>