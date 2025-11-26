<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Interfaces\NotificationRepositoryInterface;
use App\Models\User;

class NotificationService
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    ) {}

    public function sendOrderNotification(User $user, string $orderId, string $type): bool
    {
        $notificationData = $this->getOrderNotificationData($user, $orderId, $type);
        
        return $this->notificationRepository->createNotification([
            'user_id' => $user->id,
            'type' => 'order.' . $type,
            'title' => $notificationData['title'],
            'message' => $notificationData['message'],
            'action_url' => $notificationData['action_url'],
            'icon' => $notificationData['icon'],
        ]);
    }

    public function sendSystemNotification(User $user, string $title, string $message, ?string $actionUrl = null): bool
    {
        return $this->notificationRepository->createNotification([
            'user_id' => $user->id,
            'type' => 'system.general',
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'icon' => 'fas fa-info-circle',
        ]);
    }

    private function getOrderNotificationData(User $user, string $orderId, string $type): array
    {
        $notifications = [
            'placed' => [
                'title' => 'Order Placed Successfully',
                'message' => "Hello {$user->name}, your order #{$orderId} has been placed successfully.",
                'action_url' => "/orders/{$orderId}",
                'icon' => 'fas fa-shopping-bag'
            ],
            'confirmed' => [
                'title' => 'Order Confirmed',
                'message' => "Your order #{$orderId} has been confirmed and is being processed.",
                'action_url' => "/orders/{$orderId}",
                'icon' => 'fas fa-check-circle'
            ],
            'shipped' => [
                'title' => 'Order Shipped',
                'message' => "Great news! Your order #{$orderId} has been shipped.",
                'action_url' => "/orders/{$orderId}",
                'icon' => 'fas fa-shipping-fast'
            ],
            'delivered' => [
                'title' => 'Order Delivered',
                'message' => "Your order #{$orderId} has been delivered successfully.",
                'action_url' => "/orders/{$orderId}",
                'icon' => 'fas fa-box-open'
            ],
            'cancelled' => [
                'title' => 'Order Cancelled',
                'message' => "Your order #{$orderId} has been cancelled.",
                'action_url' => "/orders/{$orderId}",
                'icon' => 'fas fa-times-circle'
            ]
        ];

        return $notifications[$type] ?? [
            'title' => 'Order Update',
            'message' => "There's an update for your order #{$orderId}.",
            'action_url' => "/orders/{$orderId}",
            'icon' => 'fas fa-bell'
        ];
    }

    public function getUserNotifications(string $userId, int $perPage = 10)
    {
        return $this->notificationRepository->getUserNotifications($userId, $perPage);
    }

    public function getUnreadCount(string $userId): int
    {
        return $this->notificationRepository->getUnreadNotificationCount($userId);
    }

    public function markAsRead(string $notificationId): bool
    {
        return $this->notificationRepository->markAsRead($notificationId);
    }

    public function markAllAsRead(string $userId): bool
    {
        return $this->notificationRepository->markAllAsRead($userId);
    }
}