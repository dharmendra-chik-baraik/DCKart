<?php
// app/Repositories/NotificationRepository.php

namespace App\Repositories;

use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getUserNotifications(string $userId): Paginator
    {
        return Notification::where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->latest()
            ->paginate(10);
    }

    public function getUnreadNotifications(string $userId): Collection
    {
        return Notification::where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->whereNull('read_at')
            ->latest()
            ->get();
    }

    public function getNotificationCount(string $userId): int
    {
        return Notification::where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->count();
    }

    public function getUnreadNotificationCount(string $userId): int
    {
        return Notification::where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->whereNull('read_at')
            ->count();
    }

    public function markAsRead(string $notificationId): bool
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && !$notification->read_at) {
            $notification->update(['read_at' => now()]);
            return true;
        }
        
        return false;
    }

    public function markAllAsRead(string $userId): bool
    {
        return Notification::where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->whereNull('read_at')
            ->update(['read_at' => now()]) > 0;
    }

    public function deleteNotification(string $notificationId): bool
    {
        return Notification::where('id', $notificationId)->delete() > 0;
    }

    public function clearAllNotifications(string $userId): bool
    {
        return Notification::where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->delete() > 0;
    }

    public function createNotification(array $data): bool
    {
        try {
            Notification::create([
                'id' => $data['id'] ?? (string) \Illuminate\Support\Str::uuid(),
                'type' => $data['type'],
                'notifiable_type' => User::class,
                'notifiable_id' => $data['user_id'],
                'data' => [
                    'title' => $data['title'],
                    'message' => $data['message'],
                    'action_url' => $data['action_url'] ?? null,
                    'icon' => $data['icon'] ?? 'fas fa-bell',
                ],
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to create notification: ' . $e->getMessage());
            return false;
        }
    }
}