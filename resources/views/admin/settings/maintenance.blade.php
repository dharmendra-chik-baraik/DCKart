@extends('admin.layouts.app')

@section('title', 'Maintenance Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tools me-2"></i>Maintenance Settings
                        </h3>
                        <a href="{{ route('admin.settings.index') }}" class="link btn btn-primary float-end"><i
                                class="fas fa-arrow-left me-2"></i>Back to Settings</a>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Maintenance Mode -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header {{ $maintenanceMode ? 'bg-danger' : 'bg-success' }} text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-cog me-2"></i>Maintenance Mode
                                        </h5>
                                    </div>
                                    <div class="card-body text-center">
                                        @if ($maintenanceMode)
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                                <h4>Maintenance Mode is ACTIVE</h4>
                                                <p class="mb-0">Your site is currently unavailable to visitors.</p>
                                            </div>
                                        @else
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle fa-2x mb-3"></i>
                                                <h4>Maintenance Mode is INACTIVE</h4>
                                                <p class="mb-0">Your site is live and accessible to visitors.</p>
                                            </div>
                                        @endif

                                        <form action="{{ route('admin.settings.toggle-maintenance') }}" method="POST"
                                            class="mt-3">
                                            @csrf
                                            <input type="hidden" name="maintenance_mode"
                                                value="{{ $maintenanceMode ? '0' : '1' }}">

                                            @if ($maintenanceMode)
                                                <button type="submit" class="btn btn-success btn-lg">
                                                    <i class="fas fa-play me-2"></i>Disable Maintenance Mode
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-danger btn-lg"
                                                    onclick="return confirm('Are you sure you want to enable maintenance mode? Your site will be unavailable to visitors.')">
                                                    <i class="fas fa-pause me-2"></i>Enable Maintenance Mode
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Cache Management -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-warning">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-broom me-2"></i>Cache Management
                                        </h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <h4>Clear Application Cache</h4>
                                            <p class="mb-0">
                                                Clear cached configuration, routes, and views.
                                                This may improve performance after making changes.
                                            </p>
                                        </div>

                                        <form action="{{ route('admin.settings.clear-cache') }}" method="POST"
                                            class="mt-3">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-lg"
                                                onclick="return confirm('Are you sure you want to clear the application cache?')">
                                                <i class="fas fa-broom me-2"></i>Clear Application Cache
                                            </button>
                                        </form>

                                        <div class="mt-3 text-start">
                                            <small class="text-muted">
                                                <strong>What gets cleared:</strong>
                                                <ul class="mb-0 mt-1">
                                                    <li>Configuration cache</li>
                                                    <li>Route cache</li>
                                                    <li>Compiled views</li>
                                                    <li>Application cache</li>
                                                </ul>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-lightbulb me-2"></i>Maintenance Mode Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>When to use Maintenance Mode:</h6>
                                                <ul>
                                                    <li>Performing system updates</li>
                                                    <li>Database migrations</li>
                                                    <li>Major feature deployments</li>
                                                    <li>Security updates</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>What happens during Maintenance:</h6>
                                                <ul>
                                                    <li>Visitors see a maintenance page</li>
                                                    <li>Admin panel remains accessible</li>
                                                    <li>No new orders can be placed</li>
                                                    <li>Existing functionality may be limited</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Back Button -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <a href="{{ route('admin.settings.general') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Back to General Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
