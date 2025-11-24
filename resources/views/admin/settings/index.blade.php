@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs me-2"></i>System Settings
                    </h3>
                    <div class="card-tools">
                        <span class="badge bg-primary">Total Settings: {{ array_sum(array_column($settingsOverview, 'count')) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Maintenance Mode</h6>
                                            <span class="badge bg-{{ $maintenanceMode ? 'danger' : 'success' }}">
                                                {{ $maintenanceMode ? 'ACTIVE' : 'INACTIVE' }}
                                            </span>
                                        </div>
                                        <a href="{{ route('admin.settings.maintenance') }}" 
                                           class="btn btn-{{ $maintenanceMode ? 'success' : 'warning' }} btn-sm">
                                            <i class="fas fa-{{ $maintenanceMode ? 'play' : 'pause' }} me-1"></i>
                                            {{ $maintenanceMode ? 'Disable' : 'Enable' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Cache Management</h6>
                                            <small class="text-muted">Clear application cache</small>
                                        </div>
                                        <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm" 
                                                    onclick="return confirm('Clear application cache?')">
                                                <i class="fas fa-broom me-1"></i>Clear
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Site Status</h6>
                                            <span class="badge bg-success">LIVE</span>
                                        </div>
                                        <a href="{{ url('/') }}" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fas fa-external-link-alt me-1"></i>Visit Site
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Cards -->
                    <div class="row">
                        @foreach($settingsOverview as $group => $overview)
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card settings-card h-100">
                                <div class="card-header bg-{{ $overview['color'] }} text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="{{ $overview['icon'] }} me-2"></i>
                                        {{ ucfirst($group) }} Settings
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted">
                                        {{ $overview['description'] }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-{{ $overview['color'] }}">
                                            {{ $overview['count'] }} settings
                                        </span>
                                        <a href="{{ route('admin.settings.' . $group) }}" 
                                           class="btn btn-outline-{{ $overview['color'] }} btn-sm">
                                            <i class="fas fa-edit me-1"></i>Configure
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- System Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle me-2"></i>System Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <div class="border rounded p-3">
                                                <i class="fab fa-laravel fa-2x text-danger mb-2"></i>
                                                <h6>Laravel</h6>
                                                <span class="badge bg-dark">{{ app()->version() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="border rounded p-3">
                                                <i class="fab fa-php fa-2x text-primary mb-2"></i>
                                                <h6>PHP</h6>
                                                <span class="badge bg-primary">{{ PHP_VERSION }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="border rounded p-3">
                                                <i class="fas fa-database fa-2x text-success mb-2"></i>
                                                <h6>Database</h6>
                                                <span class="badge bg-success">MySQL</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="border rounded p-3">
                                                <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                                                <h6>Timezone</h6>
                                                <span class="badge bg-info">{{ config('app.timezone') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.settings-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.settings-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.card-header h5 {
    font-size: 1.1rem;
}

.settings-card .card-text {
    min-height: 48px;
    font-size: 0.9rem;
}
</style>
@endpush