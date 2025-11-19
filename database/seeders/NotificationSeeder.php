<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $notifications = [];

        foreach ($users as $user) {
            // Create 0-10 notifications per user
            $notificationCount = rand(0, 10);
            
            for ($i = 0; $i < $notificationCount; $i++) {
                $notifications[] = $this->createNotificationData($user->id, $user->role);
            }

            // Insert in batches to avoid memory issues
            if (count($notifications) >= 100) {
                DB::table('notifications')->insert($notifications);
                $notifications = [];
            }
        }

        // Insert any remaining notifications
        if (count($notifications) > 0) {
            DB::table('notifications')->insert($notifications);
        }

        echo "Notifications seeded: " . DB::table('notifications')->count() . "\n";
    }

    private function createNotificationData(string $userId, string $userRole): array
    {
        $types = $this->getNotificationTypesByRole($userRole);
        $type = fake()->randomElement($types);
        
        $notificationData = $this->getNotificationData($type, $userRole);

        return [
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => $type,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => json_encode($notificationData),
            'read_at' => fake()->optional(0.3)->dateTimeThisMonth(),
            'created_at' => fake()->dateTimeThisYear(),
            'updated_at' => now(),
        ];
    }

    private function getNotificationTypesByRole(string $role): array
    {
        return match($role) {
            'customer' => [
                'App\Notifications\OrderConfirmed',
                'App\Notifications\OrderShipped',
                'App\Notifications\OrderDelivered',
                'App\Notifications\NewMessage',
            ],
            'vendor' => [
                'App\Notifications\PaymentReceived',
                'App\Notifications\ProductOutOfStock',
                'App\Notifications\LowStockAlert',
                'App\Notifications\NewOrderReceived',
                'App\Notifications\ReviewSubmitted',
                'App\Notifications\NewMessage',
            ],
            'admin' => [
                'App\Notifications\VendorApplicationApproved',
                'App\Notifications\NewMessage',
                'App\Notifications\LowStockAlert',
            ],
            default => [
                'App\Notifications\OrderConfirmed',
                'App\Notifications\NewMessage',
            ],
        };
    }

    private function getNotificationData(string $type, string $userRole): array
    {
        $baseData = [
            'message' => fake()->sentence(),
            'action_url' => fake()->url(),
        ];

        // Add role-specific data
        switch ($userRole) {
            case 'customer':
                $baseData['user_type'] = 'customer';
                break;
            case 'vendor':
                $baseData['user_type'] = 'vendor';
                break;
            case 'admin':
                $baseData['user_type'] = 'admin';
                break;
        }

        switch ($type) {
            case 'App\Notifications\OrderConfirmed':
                return array_merge($baseData, [
                    'order_id' => \Illuminate\Support\Str::uuid(),
                    'order_number' => 'ORD' . fake()->numberBetween(100000, 999999),
                    'type' => 'order_confirmed',
                    'message' => 'Your order #ORD' . fake()->numberBetween(100000, 999999) . ' has been confirmed.',
                ]);

            case 'App\Notifications\OrderShipped':
                return array_merge($baseData, [
                    'order_id' => \Illuminate\Support\Str::uuid(),
                    'order_number' => 'ORD' . fake()->numberBetween(100000, 999999),
                    'tracking_number' => 'TRK' . fake()->numberBetween(1000000000, 9999999999),
                    'type' => 'order_shipped',
                    'message' => 'Your order has been shipped. Tracking: TRK' . fake()->numberBetween(1000000000, 9999999999),
                ]);

            case 'App\Notifications\OrderDelivered':
                return array_merge($baseData, [
                    'order_id' => \Illuminate\Support\Str::uuid(),
                    'order_number' => 'ORD' . fake()->numberBetween(100000, 999999),
                    'type' => 'order_delivered',
                    'message' => 'Your order #ORD' . fake()->numberBetween(100000, 999999) . ' has been delivered.',
                ]);

            case 'App\Notifications\PaymentReceived':
                return array_merge($baseData, [
                    'payment_id' => \Illuminate\Support\Str::uuid(),
                    'amount' => fake()->randomFloat(2, 10, 1000),
                    'type' => 'payment_received',
                    'message' => 'Payment of $' . fake()->randomFloat(2, 10, 1000) . ' received.',
                ]);

            case 'App\Notifications\ProductOutOfStock':
                return array_merge($baseData, [
                    'product_id' => \Illuminate\Support\Str::uuid(),
                    'product_name' => fake()->words(3, true),
                    'type' => 'product_out_of_stock',
                    'message' => 'Product "' . fake()->words(3, true) . '" is out of stock.',
                ]);

            case 'App\Notifications\LowStockAlert':
                return array_merge($baseData, [
                    'product_id' => \Illuminate\Support\Str::uuid(),
                    'product_name' => fake()->words(3, true),
                    'current_stock' => fake()->numberBetween(1, 10),
                    'type' => 'low_stock_alert',
                    'message' => 'Low stock alert: ' . fake()->words(3, true) . ' has only ' . fake()->numberBetween(1, 10) . ' items left.',
                ]);

            case 'App\Notifications\NewOrderReceived':
                return array_merge($baseData, [
                    'order_id' => \Illuminate\Support\Str::uuid(),
                    'order_number' => 'ORD' . fake()->numberBetween(100000, 999999),
                    'type' => 'new_order_received',
                    'message' => 'New order #ORD' . fake()->numberBetween(100000, 999999) . ' received.',
                ]);

            case 'App\Notifications\NewMessage':
                return array_merge($baseData, [
                    'ticket_id' => \Illuminate\Support\Str::uuid(),
                    'type' => 'new_message',
                    'message' => 'You have a new support message.',
                ]);

            case 'App\Notifications\VendorApplicationApproved':
                return array_merge($baseData, [
                    'type' => 'vendor_application_approved',
                    'message' => 'Vendor application approved successfully.',
                ]);

            case 'App\Notifications\ReviewSubmitted':
                return array_merge($baseData, [
                    'product_id' => \Illuminate\Support\Str::uuid(),
                    'product_name' => fake()->words(3, true),
                    'rating' => fake()->numberBetween(1, 5),
                    'type' => 'review_submitted',
                    'message' => 'New ' . fake()->numberBetween(1, 5) . '-star review for ' . fake()->words(3, true),
                ]);

            default:
                return $baseData;
        }
    }
}