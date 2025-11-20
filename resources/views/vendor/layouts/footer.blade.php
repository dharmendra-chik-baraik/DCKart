<footer class="mt-auto py-3 bg-light border-top">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="text-muted">&copy; {{ date('Y') }} {{ config('app.name', 'MultiVendor') }} - Vendor Panel</span>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="text-muted">Store: {{ Auth::user()->vendor->store_name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</footer>