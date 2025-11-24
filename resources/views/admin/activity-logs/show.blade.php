@extends('admin.layouts.app')

@section('title', 'Activity Log Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Activity Logs
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>Activity Log Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Basic Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-semibold" style="width: 120px;">ID:</td>
                                            <td>
                                                <code>{{ $activityLog->id }}</code>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Action:</td>
                                            <td>
                                                <span class="badge bg-{{ $activityLog->action_badge }}">
                                                    {{ ucfirst($activityLog->action) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Module:</td>
                                            <td>
                                                <i class="{{ $activityLog->module_icon }} me-2 text-primary"></i>
                                                {{ ucfirst($activityLog->module) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">IP Address:</td>
                                            <td>
                                                <code>{{ $activityLog->ip_address }}</code>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Timestamp:</td>
                                            <td>
                                                <div>{{ $activityLog->formatted_time }}</div>
                                                <small class="text-muted">{{ $activityLog->time_ago }}</small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user me-2"></i>User Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($activityLog->user)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-lg bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user fa-lg text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $activityLog->user->name }}</h6>
                                            <p class="text-muted mb-1">{{ $activityLog->user->email }}</p>
                                            <span class="badge bg-{{ $activityLog->user->role === 'admin' ? 'danger' : ($activityLog->user->role === 'vendor' ? 'warning' : 'info') }}">
                                                {{ ucfirst($activityLog->user->role) }}
                                            </span>
                                        </div>
                                    </div>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-semibold" style="width: 100px;">User ID:</td>
                                            <td><code>{{ $activityLog->user->id }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Status:</td>
                                            <td>
                                                <span class="badge bg-{{ $activityLog->user->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($activityLog->user->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Last Login:</td>
                                            <td>
                                                @if($activityLog->user->last_login_at)
                                                    {{ $activityLog->user->last_login_at->format('M j, Y g:i A') }}
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    @else
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-user-slash fa-2x mb-2"></i>
                                        <p>User account no longer exists</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-file-alt me-2"></i>Description
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($activityLog->description)
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-sticky-note me-2"></i>
                                            {{ $activityLog->description }}
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-comment-slash fa-2x mb-2"></i>
                                            <p>No description provided for this activity</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <form action="{{ route('admin.activity-logs.destroy', $activityLog) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete this activity log?')">
                                            <i class="fas fa-trash me-2"></i> Delete Log
                                        </button>
                                    </form>
                                </div>
                                <div>
                                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back to List
                                    </a>
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
.avatar-lg {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

.card-header.bg-light {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush