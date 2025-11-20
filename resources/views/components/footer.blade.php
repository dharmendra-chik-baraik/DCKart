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
                    <a href="#" class="text-white me-3">
                        <i class="fab fa-facebook-f fa-lg"></i>
                    </a>
                    <a href="#" class="text-white me-3">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="text-white me-3">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="#" class="text-white me-3">
                        <i class="fab fa-linkedin-in fa-lg"></i>
                    </a>
                    <a href="#" class="text-white">
                        <i class="fab fa-youtube fa-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ url('/') }}" class="text-light text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Contact</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">FAQs</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Blog</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4">Customer Service</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Help Center</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Returns</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Shipping Info</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Track Order</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Size Guide</a></li>
                </ul>
            </div>

            <!-- For Vendors -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4">For Vendors</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Sell on Platform</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Vendor Dashboard</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Vendor Guide</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Commission Rates</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Vendor Support</a></li>
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

        <!-- Newsletter Section -->
        <div class="row mt-5 pt-4 border-top border-secondary">
            <div class="col-lg-6">
                <h5 class="fw-bold mb-3">Subscribe to Our Newsletter</h5>
                <p class="text-light mb-3">Get the latest updates on new products and upcoming sales</p>
                <form class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="Your email address" required>
                    <button type="submit" class="btn btn-primary px-4">Subscribe</button>
                </form>
            </div>
            <div class="col-lg-6">
                <h5 class="fw-bold mb-3">Download Our App</h5>
                <p class="text-light mb-3">Shop on the go with our mobile app</p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-light">
                        <i class="fab fa-google-play me-2"></i>Google Play
                    </a>
                    <a href="#" class="btn btn-outline-light">
                        <i class="fab fa-apple me-2"></i>App Store
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="row mt-5 pb-4 pt-4 border-top border-secondary">
            <div class="col-md-6">
                <p class="text-light mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'MultiVendor') }}. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="footer-links">
                    <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-light text-decoration-none me-3">Terms of Service</a>
                    <a href="#" class="text-light text-decoration-none">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
footer {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
}

footer a {
    transition: color 0.3s ease;
}

footer a:hover {
    color: #667eea !important;
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
    background: #667eea;
    transform: translateY(-2px);
}

.footer-brand {
    font-size: 1.5rem;
}

.border-secondary {
    border-color: #4a5f7a !important;
}

.btn-outline-light:hover {
    background-color: #667eea;
    border-color: #667eea;
}

@media (max-width: 768px) {
    .footer-links {
        text-align: left !important;
        margin-top: 1rem;
    }
    
    .footer-links a {
        display: block;
        margin: 0.5rem 0;
    }
}
</style>