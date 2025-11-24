@extends('admin.layouts.app')

@section('title', 'Pages Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pages Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create New Page
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('admin.pages.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search pages..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Pages Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                <tr>
                                    <td>{{ $page->title }}</td>
                                    <td>{{ $page->slug }}</td>
                                    <td>
                                        <span class="badge badge-{{ $page->status ? 'success' : 'danger' }}">
                                            {{ $page->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $page->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.pages.edit', $page) }}" 
                                               class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-{{ $page->status ? 'warning' : 'success' }} toggle-status" 
                                                    data-page-id="{{ $page->id }}" 
                                                    title="{{ $page->status ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $page->status ? 'times' : 'check' }}"></i>
                                            </button>
                                            <form action="{{ route('admin.pages.destroy', $page) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this page?')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No pages found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $pages->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-status').click(function() {
        const pageId = $(this).data('page-id');
        const button = $(this);
        
        $.ajax({
            url: "{{ url('admin/pages') }}/" + pageId + "/toggle-status",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    // Update button appearance
                    if (response.status) {
                        button.removeClass('btn-success').addClass('btn-warning')
                              .html('<i class="fas fa-times"></i>')
                              .attr('title', 'Deactivate');
                        button.closest('tr').find('.badge')
                              .removeClass('badge-danger').addClass('badge-success')
                              .text('Active');
                    } else {
                        button.removeClass('btn-warning').addClass('btn-success')
                              .html('<i class="fas fa-check"></i>')
                              .attr('title', 'Activate');
                        button.closest('tr').find('.badge')
                              .removeClass('badge-success').addClass('badge-danger')
                              .text('Inactive');
                    }
                    
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('Error updating page status.');
            }
        });
    });
});
</script>
@endpush