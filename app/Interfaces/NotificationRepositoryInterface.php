<?php
// app/Interfaces/NotificationRepositoryInterface.php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface NotificationRepositoryInterface
{
    public function getUserNotifications(string $userId): Paginator;
    public function getUnreadNotifications(string $userId): Collection;
    public function getNotificationCount(string $userId): int;
    public function getUnreadNotificationCount(string $userId): int;
    public function markAsRead(string $notificationId): bool;
    public function markAllAsRead(string $userId): bool;
    public function deleteNotification(string $notificationId): bool;
    public function clearAllNotifications(string $userId): bool;
    public function createNotification(array $data): bool;
}