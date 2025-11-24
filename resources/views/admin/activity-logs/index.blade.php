@extends('admin.layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history me-2"></i>Activity Logs
                    </h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-cog me-1"></i> Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('admin.activity-logs.clear-old') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item" 
                                                onclick="return confirm('Clear all logs older than 30 days?')">
                                            <i class="fas fa-broom me-2"></i> Clear Old Logs
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <a href="{{ route('admin.activity-logs.export') }}" class="dropdown-item">
                                        <i class="fas fa-download me-2"></i> Export Logs
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form action="{{ route('admin.activity-logs.index') }}" method="GET" id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Search logs..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="module" class="form-control">
                                            <option value="">All Modules</option>
                                            @foreach($filterOptions['modules'] as $module)
                                                <option value="{{ $module }}" 
                                                    {{ request('module') == $module ? 'selected' : '' }}>
                                                    {{ ucfirst($module) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="action" class="form-control">
                                            <option value="">All Actions</option>
                                            @foreach($filterOptions['actions'] as $action)
                                                <option value="{{ $action }}" 
                                                    {{ request('action') == $action ? 'selected' : '' }}>
                                                    {{ ucfirst($action) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="user_id" class="form-control">
                                            <option value="">All Users</option>
                                            @foreach($filterOptions['users'] as $user)
                                                <option value="{{ $user->id }}" 
                                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter me-1"></i> Filter
                                            </button>
                                            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Activity Logs Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Module</th>
                                    <th>Description</th>
                                    <th>IP Address</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityLogs as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded me-2">
                                                <i class="fas fa-user fa-sm p-2"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $log->user->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $log->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $log->action_badge }}">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $log->module_icon }} me-2 text-primary"></i>
                                            {{ ucfirst($log->module) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-truncate" style="max-width: 200px;" 
                                              title="{{ $log->description }}">
                                            {{ $log->description ?? 'No description' }}
                                        </span>
                                    </td>
                                    <td>
                                        <code>{{ $log->ip_address }}</code>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>{{ $log->formatted_time }}</div>
                                            <small class="text-muted">{{ $log->time_ago }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.activity-logs.destroy', $log) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Delete this activity log?')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No activity logs found</h5>
                                        <p class="text-muted">Activity logs will appear here as users perform actions.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                            {{ $activityLogs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
    max-width: 200px;
    vertical-align: middle;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filter selects change
    const filterSelects = document.querySelectorAll('select[name="module"], select[name="action"], select[name="user_id"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endpush