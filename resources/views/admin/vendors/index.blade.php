<!-- resources/views/admin/vendors/index.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Vendors Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Vendors Management</h1>
        <div class="btn-group">
            <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Vendor
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.vendors.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control" placeholder="Shop name, slug, or vendor name...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">User Status</label>
                    <select name="user_status" class="form-control">
                        <option value="">All User Status</option>
                        <option value="active" {{ request('user_status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('user_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('user_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Business Status</label>
                    <select name="vendor_status" class="form-control">
                        <option value="">All Business Status</option>
                        <option value="pending" {{ request('vendor_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('vendor_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="suspended" {{ request('vendor_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="rejected" {{ request('vendor_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Verification</label>
                    <select name="verified" class="form-control">
                        <option value="">All</option>
                        <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>Verified</option>
                        <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>Not Verified</option>
                    </select>
                </div>
                <div class="col-12 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Vendors Table -->
    <div class="card shadow-sm">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Vendors List</h6>
            <span class="badge bg-primary">Total: {{ $vendors->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Vendor</th>
                            <th>Shop Information</th>
                            <th>Contact</th>
                            <th>User Status</th>
                            <th>Business Status</th>
                            <th>Verification</th>
                            <th>Products</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                        <tr>
                            <td>{{ $loop->iteration + ($vendors->perPage() * ($vendors->currentPage() - 1)) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; font-size: 14px;">
                                        {{ strtoupper(substr($vendor->user->name, 0, 2)) }}
                                    </div>
                                    <div class="ms-3">
                                        <strong>{{ $vendor->user->name }}</strong>
                                        <br><small class="text-muted">{{ $vendor->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $vendor->shop_name }}</strong>
                                <br><small class="text-muted">/{{ $vendor->shop_slug }}</small>
                                @if($vendor->description)
                                <br><small class="text-muted">{{ Str::limit($vendor->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-phone text-muted me-1"></i>{{ $vendor->phone ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge 
                                    @if($vendor->user->status == 'active') bg-success
                                    @elseif($vendor->user->status == 'inactive') bg-secondary
                                    @elseif($vendor->user->status == 'suspended') bg-danger
                                    @else bg-secondary @endif">
                                    {{ ucfirst($vendor->user->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($vendor->status == 'approved') bg-success
                                    @elseif($vendor->status == 'pending') bg-warning
                                    @elseif($vendor->status == 'suspended') bg-danger
                                    @elseif($vendor->status == 'rejected') bg-secondary
                                    @else bg-secondary @endif">
                                    {{ ucfirst($vendor->status) }}
                                </span>
                            </td>
                            <td>
                                @if($vendor->verified_at)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $vendor->products_count ?? $vendor->products->count() }}</span>
                            </td>
                            <td>{{ $vendor->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.vendors.show', $vendor->id) }}" 
                                       class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.vendors.edit', $vendor->id) }}" 
                                       class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Actions Dropdown -->
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <!-- Business Status Actions -->
                                            <li class="dropdown-header">Business Actions</li>
                                            @if($vendor->status != 'approved')
                                            <li>
                                                <form action="{{ route('admin.vendors.change-status', $vendor->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-check me-2"></i>Approve Business
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            @if($vendor->status != 'suspended')
                                            <li>
                                                <form action="{{ route('admin.vendors.change-status', $vendor->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="suspended">
                                                    <button type="submit" class="dropdown-item text-warning">
                                                        <i class="fas fa-pause me-2"></i>Suspend Business
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            @if($vendor->status != 'rejected')
                                            <li>
                                                <form action="{{ route('admin.vendors.change-status', $vendor->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-times me-2"></i>Reject Business
                                                    </button>
                                                </form>
                                            </li>
                                            @endif

                                            <!-- User Status Actions -->
                                            <li class="dropdown-header">User Actions</li>
                                            @if($vendor->user->status != 'active')
                                            <li>
                                                <form action="{{ route('admin.users.change-status', $vendor->user->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="active">
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-user-check me-2"></i>Activate User
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            @if($vendor->user->status != 'suspended')
                                            <li>
                                                <form action="{{ route('admin.users.change-status', $vendor->user->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="suspended">
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-user-slash me-2"></i>Suspend User
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            
                                            <!-- Verification Toggle -->
                                            <li class="dropdown-header">Verification</li>
                                            <li>
                                                <form action="{{ route('admin.vendors.toggle-verification', $vendor->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item {{ $vendor->verified_at ? 'text-warning' : 'text-success' }}">
                                                        <i class="fas {{ $vendor->verified_at ? 'fa-times' : 'fa-check' }} me-2"></i>
                                                        {{ $vendor->verified_at ? 'Unverify' : 'Verify' }}
                                                    </button>
                                                </form>
                                            </li>
                                            
                                            <li><hr class="dropdown-divider"></li>
                                            
                                            <!-- Delete -->
                                            <li>
                                                <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this vendor? This will also delete their user account.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-store fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No vendors found</h5>
                                <p class="text-muted">Try adjusting your filters or add a new vendor.</p>
                                <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Add First Vendor
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($vendors->hasPages())
            <div class="d-flex custom-pagination justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $vendors->firstItem() }} to {{ $vendors->lastItem() }} of {{ $vendors->total() }} results
                </div>
                <nav>
                    {{ $vendors->links('pagination::bootstrap-5') }}
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .table th,
        .table td {
            vertical-align: middle;
        }
        .custom-pagination nav{
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .dropdown-header {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            padding: 0.25rem 1rem;
            text-transform: uppercase;
        }
    </style>
@endpush