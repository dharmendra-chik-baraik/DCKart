{{-- resources/views/components/notification-dropdown.blade.php --}}
<li class="nav-item dropdown">
    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-count">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end shadow" style="width: 350px;">
        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Notifications</h6>
            @if($unreadCount > 0)
                <button class="btn btn-sm btn-outline-primary" id="dropdownMarkAllRead">
                    Mark all read
                </button>
            @endif
        </div>
        <div class="dropdown-notifications-list">
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <a href="{{ $notification->action_url ?? '#' }}" class="dropdown-item notification-item {{ $notification->isRead() ? '' : 'unread' }}">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1">{{ Str::limit($notification->title, 40) }}</h6>
                                <p class="mb-0 small text-muted">{{ Str::limit($notification->message, 60) }}</p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </a>
                @endforeach
                <div class="dropdown-footer text-center">
                    <a href="{{ route('notifications.index') }}" class="text-primary">View all notifications</a>
                </div>
            @else
                <div class="dropdown-item text-center text-muted py-3">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i>
                    <p class="mb-0">No notifications</p>
                </div>
            @endif
        </div>
    </div>
</li>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark all as read from dropdown
    document.getElementById('dropdownMarkAllRead')?.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        fetch('{{ route("notifications.markAllAsRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  // Remove unread styles and update count
                  document.querySelectorAll('.notification-item.unread').forEach(item => {
                      item.classList.remove('unread');
                  });
                  document.querySelectorAll('.notification-count').forEach(count => {
                      count.remove();
                  });
                  this.remove();
              }
          });
    });
});
</script>