<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        $notifications = $this->notificationService->getUserNotifications($request->user()->id);
        $unreadCount = $this->notificationService->getUnreadCount($request->user()->id);

        if ($request->wantsJson()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
        }

        return view('customer.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(string $id): JsonResponse
    {
        $success = $this->notificationService->markAsRead($id);
        
        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notification marked as read' : 'Notification not found'
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $success = $this->notificationService->markAllAsRead($request->user()->id);
        
        return response()->json([
            'success' => $success,
            'message' => $success ? 'All notifications marked as read' : 'No notifications to mark'
        ]);
    }

    public function getUnreadCount(Request $request): JsonResponse
    {
        $count = $this->notificationService->getUnreadCount($request->user()->id);
        
        return response()->json([
            'unread_count' => $count
        ]);
    }
}