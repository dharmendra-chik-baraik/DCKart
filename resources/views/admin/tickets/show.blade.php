@extends('admin.layouts.app')

@section('title', 'Ticket #' . $ticket->id . ' - Admin Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ticket #{{ $ticket->id }}</h1>
        <div>
            <a href="{{ route('admin.tickets.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Tickets
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Ticket Messages -->
        <div class="col-lg-9">
            <!-- Ticket Header -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $ticket->subject }}</h6>
                    <div class="d-flex align-items-center">
                        <span class="badge badge-{{ getPriorityBadge($ticket->priority) }} mr-2 p-2">
                            <i class="fas {{ getPriorityIcon($ticket->priority) }}"></i>
                            {{ ucfirst($ticket->priority) }}
                        </span>
                        <span class="badge badge-{{ getTicketStatusBadge($ticket->status) }} p-2">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th width="40%">Created By</th>
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
                                </tr>
                                <tr>
                                    <th>Vendor</th>
                                    <td>
                                        @if($ticket->vendor)
                                        <div class="d-flex align-items-center">
                                            @if($ticket->vendor->logo)
                                            <img src="{{ asset('storage/' . $ticket->vendor->logo) }}" 
                                                 alt="{{ $ticket->vendor->shop_name }}" 
                                                 class="rounded-circle mr-2" width="30" height="30">
                                            @else
                                            <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center mr-2" 
                                                 style="width: 30px; height: 30px;">
                                                <i class="fas fa-store"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $ticket->vendor->shop_name }}</strong><br>
                                                <small class="text-muted">{{ $ticket->vendor->user->email }}</small>
                                            </div>
                                        </div>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th width="40%">Response Time</th>
                                    <td>
                                        @if($responseTime)
                                        <span class="text-success">
                                            <i class="fas fa-clock"></i> {{ $responseTime }} hours
                                        </span>
                                        @else
                                        <span class="text-muted">No response yet</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created Date</th>
                                    <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Thread -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Conversation</h6>
                </div>
                <div class="card-body">
                    <div class="ticket-messages">
                        @foreach($ticket->messages as $message)
                        <div class="message mb-4 {{ $message->sender_id == auth()->id() ? 'message-sent' : 'message-received' }}">
                            <div class="message-header d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle {{ $message->sender_id == auth()->id() ? 'bg-primary' : 'bg-secondary' }} text-white d-flex align-items-center justify-content-center mr-2" 
                                         style="width: 35px; height: 35px; font-size: 14px;">
                                        {{ substr($message->sender->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $message->sender->name }}</strong>
                                        <div class="text-muted small">
                                            {{ $message->created_at->format('M d, Y H:i') }}
                                            @if($message->sender->role === 'admin')
                                            <span class="badge badge-info ml-1">Staff</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="message-body">
                                <div class="message-content p-3 rounded {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
                                    {!! nl2br(e($message->message)) !!}
                                </div>
                                @if($message->attachment)
                                <div class="message-attachment mt-2">
                                    <a href="{{ route('admin.tickets.download-attachment', $message->attachment) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-paperclip"></i> Download Attachment
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        @if($ticket->messages->count() === 0)
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>No messages yet. Start the conversation.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Reply Form -->
            @if($ticket->status !== 'closed' && $ticket->status !== 'resolved')
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reply to Ticket</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tickets.add-message', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="message">Your Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" 
                                      placeholder="Type your response here..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="attachment">Attachment</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="attachment" name="attachment">
                                <label class="custom-file-label" for="attachment">Choose file (max: 10MB)</label>
                            </div>
                            <small class="form-text text-muted">
                                Supported files: images, documents, PDFs (max 10MB)
                            </small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Send Reply
                            </button>
                            <button type="button" class="btn btn-success" onclick="markAsResolved()">
                                <i class="fas fa-check"></i> Mark as Resolved
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle"></i> Ticket Closed</h6>
                <p class="mb-0">This ticket has been {{ $ticket->status }}. You cannot send new messages.</p>
            </div>
            @endif
        </div>

        <!-- Ticket Actions & Info -->
        <div class="col-lg-3">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <!-- Status Update -->
                    <div class="form-group">
                        <label>Update Status</label>
                        <form action="{{ route('admin.tickets.change-status', $ticket->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <select class="form-control" name="status" onchange="this.form.submit()">
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Priority Update -->
                    <div class="form-group">
                        <label>Priority</label>
                        <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="input-group">
                                <select class="form-control" name="priority" onchange="this.form.submit()">
                                    <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <!-- Additional Actions -->
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $ticket->user->email }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-envelope"></i> Email Customer
                        </a>
                        
                        @if($ticket->vendor)
                        <a href="mailto:{{ $ticket->vendor->user->email }}" class="btn btn-outline-warning btn-block">
                            <i class="fas fa-store"></i> Email Vendor
                        </a>
                        @endif

                        @if($ticket->status !== 'closed')
                        <button type="button" class="btn btn-outline-success btn-block" onclick="markAsResolved()">
                            <i class="fas fa-check"></i> Mark Resolved
                        </button>
                        @endif

                        <button type="button" class="btn btn-outline-danger btn-block" 
                                onclick="confirmDelete('{{ $ticket->id }}', '{{ addslashes($ticket->subject) }}')">
                            <i class="fas fa-trash"></i> Delete Ticket
                        </button>
                    </div>
                </div>
            </div>

            <!-- Ticket Information -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ticket Information</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ticket ID:</span>
                            <strong>#{{ $ticket->id }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Created:</span>
                            <span>{{ $ticket->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Last Updated:</span>
                            <span>{{ $ticket->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Messages:</span>
                            <span>{{ $ticket->messages->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Customer Since:</span>
                            <span>{{ $ticket->user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Mark as Resolved Form -->
<form id="resolveForm" action="{{ route('admin.tickets.change-status', $ticket->id) }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="status" value="resolved">
</form>
@endsection

@push('scripts')
<script>
    // Update custom file input label
    document.getElementById('attachment').addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || 'Choose file';
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    function markAsResolved() {
        if (confirm('Are you sure you want to mark this ticket as resolved?')) {
            document.getElementById('resolveForm').submit();
        }
    }

    function confirmDelete(ticketId, subject) {
        if (confirm(`Are you sure you want to delete ticket: "${subject}"?`)) {
            const form = document.getElementById('deleteForm');
            form.action = `{{ url('admin/tickets') }}/${ticketId}`;
            form.submit();
        }
    }
</script>

<style>
.message-sent .message-content {
    margin-left: 50px;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    border-top-right-radius: 5px;
}

.message-received .message-content {
    margin-right: 50px;
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
    border-top-left-radius: 5px;
}

.ticket-messages {
    max-height: 600px;
    overflow-y: auto;
    padding: 10px;
}
</style>
@endpush