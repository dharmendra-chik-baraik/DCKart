@extends('admin.layouts.app')

@section('title', 'Support Tickets - Admin Panel')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Support Tickets</h1>
            <a href="{{ route('admin.tickets.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Create Ticket
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tickets</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Open</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['open'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Progress</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-spinner fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Resolved</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['resolved'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.tickets.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="open" {{ $filters['status'] == 'open' ? 'selected' : '' }}>Open
                                    </option>
                                    <option value="in_progress" {{ $filters['status'] == 'in_progress' ? 'selected' : '' }}>
                                        In Progress</option>
                                    <option value="resolved" {{ $filters['status'] == 'resolved' ? 'selected' : '' }}>
                                        Resolved</option>
                                    <option value="closed" {{ $filters['status'] == 'closed' ? 'selected' : '' }}>Closed
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="">All Priority</option>
                                    <option value="low" {{ $filters['priority'] == 'low' ? 'selected' : '' }}>Low
                                    </option>
                                    <option value="medium" {{ $filters['priority'] == 'medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="high" {{ $filters['priority'] == 'high' ? 'selected' : '' }}>High
                                    </option>
                                    <option value="urgent" {{ $filters['priority'] == 'urgent' ? 'selected' : '' }}>Urgent
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="date" class="form-control" id="date_from" name="date_from"
                                    value="{{ $filters['date_from'] }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="date" class="form-control" id="date_to" name="date_to"
                                    value="{{ $filters['date_to'] }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="search">Search</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    placeholder="Search by subject, ticket ID, user name..."
                                    value="{{ $filters['search'] }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">All Tickets</h6>
                <div class="bulk-actions" style="display: none;">
                    <select class="form-control form-control-sm d-inline-block w-auto me-2" id="bulkAction">
                        <option value="">Bulk Actions</option>
                        <option value="status">Change Status</option>
                        <option value="priority">Change Priority</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button class="btn btn-sm btn-primary" onclick="showBulkActionModal()">Apply</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Ticket ID</th>
                                <th>Subject</th>
                                <th>User</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Last Update</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="ticket-checkbox" value="{{ $ticket->id }}">
                                    </td>
                                    <td>
                                        <strong>#{{ $ticket->id }}</strong>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                                            class="text-primary font-weight-bold">
                                            {{ Str::limit($ticket->subject, 50) }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            {{ $ticket->created_at->format('M d, Y') }}
                                            • {{ $ticket->messages_count ?? 0 }} messages
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2"
                                                style="width: 30px; height: 30px; font-size: 12px;">
                                                {{ substr($ticket->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $ticket->user->name }}</strong><br>
                                                <small class="text-muted">{{ $ticket->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ getPriorityBadge($ticket->priority) }} p-2">
                                            <i class="fas {{ getPriorityIcon($ticket->priority) }}"></i>
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ getTicketStatusBadge($ticket->status) }} p-2">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->updated_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                                                class="btn btn-sm btn-outline-info" title="View Ticket">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if ($ticket->status !== 'closed')
                                                <form action="{{ route('admin.tickets.change-status', $ticket->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="resolved">
                                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                                        title="Mark Resolved"
                                                        onclick="return confirm('Mark this ticket as resolved?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.tickets.destroy', $ticket->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Delete Ticket"
                                                    onclick="return confirm('Are you sure you want to delete this ticket?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $tickets->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Action Modal -->
    <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkActionModalLabel">Bulk Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="bulkActionContent">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="applyBulkAction()">Apply Action</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        console.log('Admin Tickets Page Loaded');

        // Bulk selection functionality
        document.addEventListener('DOMContentLoaded', function() {
            initializeBulkActions();
        });

        function initializeBulkActions() {
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.ticket-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    toggleBulkActions();
                });
            }

            document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', toggleBulkActions);
            });
        }

        function toggleBulkActions() {
            const checkedCount = document.querySelectorAll('.ticket-checkbox:checked').length;
            const bulkActions = document.querySelector('.bulk-actions');
            if (bulkActions) {
                if (checkedCount > 0) {
                    bulkActions.style.display = 'block';
                } else {
                    bulkActions.style.display = 'none';
                }
            }
        }

        function showBulkActionModal() {
            const action = document.getElementById('bulkAction').value;
            const checkedTickets = Array.from(document.querySelectorAll('.ticket-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (!action) {
                alert('Please select an action');
                return;
            }

            if (checkedTickets.length === 0) {
                alert('Please select at least one ticket');
                return;
            }

            let content = '';
            switch (action) {
                case 'status':
                    content = `
                    <div class="form-group">
                        <label>Change Status To</label>
                        <select class="form-control" id="bulkStatus">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                `;
                    break;
                case 'priority':
                    content = `
                    <div class="form-group">
                        <label>Change Priority To</label>
                        <select class="form-control" id="bulkPriority">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                `;
                    break;
                case 'delete':
                    content = `
                    <div class="alert alert-danger">
                        <h6>Confirm Deletion</h6>
                        <p class="mb-0">Are you sure you want to delete ${checkedTickets.length} ticket(s)? This action cannot be undone.</p>
                    </div>
                `;
                    break;
            }

            document.getElementById('bulkActionContent').innerHTML = content;

            // Use Bootstrap 5 modal
            const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
            modal.show();
        }

        function applyBulkAction() {
            const action = document.getElementById('bulkAction').value;
            const checkedTickets = Array.from(document.querySelectorAll('.ticket-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (checkedTickets.length === 0) {
                alert('No tickets selected');
                return;
            }

            let formData = {
                ticket_ids: checkedTickets,
                action: action
            };

            // Get additional data based on action
            switch (action) {
                case 'status':
                    const statusSelect = document.getElementById('bulkStatus');
                    if (statusSelect) {
                        formData.status = statusSelect.value;
                    }
                    break;
                case 'priority':
                    const prioritySelect = document.getElementById('bulkPriority');
                    if (prioritySelect) {
                        formData.priority = prioritySelect.value;
                    }
                    break;
                case 'delete':
                    // No additional data needed for delete
                    break;
            }

            console.log('Sending bulk action:', formData);

            // Show loading state
            const submitBtn = document.querySelector('#bulkActionModal .btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;

            fetch('{{ route('admin.tickets.bulk-update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        return response.text().then(text => {
                            throw new Error('Server returned HTML. Possible server error.');
                        });
                    }
                })
                .then(data => {
                    console.log('Bulk action response:', data);

                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('bulkActionModal'));
                    modal.hide();

                    if (data.success) {
                        // Show success message
                        showAlert('success', data.message || 'Action completed successfully');
                        // Reload after a short delay
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('error', data.message || 'Action failed');
                    }
                })
                .catch(error => {
                    console.error('Bulk action error:', error);
                    showAlert('error', 'Error: ' + error.message);
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        }

        function showAlert(type, message) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.custom-alert');
            existingAlerts.forEach(alert => alert.remove());

            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `
            <div class="alert ${alertClass} custom-alert alert-dismissible fade show" role="alert" 
                 style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

            document.body.insertAdjacentHTML('beforeend', alertHtml);

            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.custom-alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    </script>
@endpush
