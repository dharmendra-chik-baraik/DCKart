@extends('admin.layouts.app')

@section('title', 'Create Support Ticket - Admin Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Support Ticket</h1>
        <a href="{{ route('admin.tickets.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Tickets
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ticket Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data" id="ticketForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" class="form-control" id="subject" name="subject" 
                                   value="{{ old('subject') }}" placeholder="Enter ticket subject" required>
                            @error('subject')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Customer *</label>
                                    <select class="form-control select2" id="user_id" name="user_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vendor_id">Vendor (Optional)</label>
                                    <select class="form-control select2" id="vendor_id" name="vendor_id">
                                        <option value="">Select Vendor (Optional)</option>
                                        @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->shop_name }} ({{ $vendor->user->email }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('vendor_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priority *</label>
                                    <select class="form-control" id="priority" name="priority" required>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- REMOVED: assigned_to field -->
                        </div>

                        <div class="form-group">
                            <label for="message">Initial Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="8" 
                                      placeholder="Enter the initial message for this ticket..." required>{{ old('message') }}</textarea>
                            @error('message')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="attachment">Attachment (Optional)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="attachment" name="attachment">
                                <label class="custom-file-label" for="attachment">Choose file (max: 10MB)</label>
                            </div>
                            <small class="form-text text-muted">
                                Supported files: images, documents, PDFs (max 10MB)
                            </small>
                            @error('attachment')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Ticket Creation Information</h6>
                                <ul class="mb-0">
                                    <li>Ticket will be created with <strong>Open</strong> status</li>
                                    <li>Customer will be notified about the new ticket</li>
                                    <li>You can add more messages after creating the ticket</li>
                                </ul>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-ticket-alt"></i> Create Ticket
                        </button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Help -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Priority Guidelines</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <span class="badge badge-danger mr-2">Urgent</span>
                                    Critical Issues
                                </h6>
                            </div>
                            <p class="mb-1 small">System outages, security issues, payment failures</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <span class="badge badge-warning mr-2">High</span>
                                    Important Issues
                                </h6>
                            </div>
                            <p class="mb-1 small">Order problems, account access, major bugs</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <span class="badge badge-info mr-2">Medium</span>
                                    Standard Issues
                                </h6>
                            </div>
                            <p class="mb-1 small">Feature questions, minor bugs, general support</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <span class="badge badge-success mr-2">Low</span>
                                    Minor Issues
                                </h6>
                            </div>
                            <p class="mb-1 small">UI suggestions, documentation, non-critical</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tickets -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Tickets</h6>
                </div>
                <div class="card-body">
                    @php
                        $recentTickets = app(App\Services\TicketService::class)->getRecentTickets(5);
                    @endphp
                    
                    @if($recentTickets->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentTickets as $recentTicket)
                        <a href="{{ route('admin.tickets.show', $recentTicket->id) }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ Str::limit($recentTicket->subject, 30) }}</h6>
                                <small class="text-{{ getTicketStatusBadge($recentTicket->status) }}">
                                    {{ ucfirst($recentTicket->status) }}
                                </small>
                            </div>
                            <p class="mb-1 small text-muted">
                                {{ $recentTicket->user->name }} • {{ $recentTicket->created_at->diffForHumans() }}
                            </p>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center">No recent tickets found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select option",
            allowClear: true
        });
    });

    // Update custom file input label
    document.getElementById('attachment').addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || 'Choose file';
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // Form validation
    document.getElementById('ticketForm').addEventListener('submit', function(e) {
        const subject = document.getElementById('subject').value.trim();
        const message = document.getElementById('message').value.trim();
        
        if (!subject) {
            e.preventDefault();
            alert('Please enter a subject for the ticket');
            document.getElementById('subject').focus();
            return false;
        }
        
        if (!message) {
            e.preventDefault();
            alert('Please enter a message for the ticket');
            document.getElementById('message').focus();
            return false;
        }
    });
</script>
@endpush