{{-- resources/views/customer/notifications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifications</h5>
                    <div>
                        @if($unreadCount > 0)
                            <button id="markAllRead" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check-double me-1"></i> Mark All as Read
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ $notification->isRead() ? '' : 'bg-light' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} fa-lg text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">{{ $notification->message }}</p>
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                            @endif
                                        </div>
                                        @if(!$notification->isRead())
                                            <div class="flex-shrink-0 ms-3">
                                                <button class="btn btn-sm btn-outline-success mark-as-read" data-id="{{ $notification->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5>No notifications</h5>
                            <p class="text-muted">You don't have any notifications yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Mark as read
    $('.mark-as-read').click(function() {
        const button = $(this);
        const notificationId = button.data('id');
        
        $.ajax({
            url: '{{ route("notifications.markAsRead", "") }}/' + notificationId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    button.closest('.list-group-item').removeClass('bg-light');
                    button.remove();
                    updateUnreadCount();
                }
            }
        });
    });
    
    // Mark all as read
    $('#markAllRead').click(function() {
        $.ajax({
            url: '{{ route("notifications.markAllAsRead") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('.list-group-item').removeClass('bg-light');
                    $('.mark-as-read').remove();
                    $('#markAllRead').remove();
                    updateUnreadCount();
                }
            }
        });
    });
    
    function updateUnreadCount() {
        $.get('{{ route("notifications.unreadCount") }}', function(response) {
            // Update any unread count indicators in your layout
            $('.notification-count').text(response.unread_count);
            if (response.unread_count === 0) {
                $('.notification-count').hide();
            }
        });
    }
});
</script>
@endpush