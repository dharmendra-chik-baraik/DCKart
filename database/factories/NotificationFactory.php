<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        $types = [
            'App\Notifications\OrderConfirmed',
            'App\Notifications\OrderShipped',
            'App\Notifications\OrderDelivered',
            'App\Notifications\PaymentReceived',
            'App\Notifications\ProductOutOfStock',
            'App\Notifications\NewMessage',
            'App\Notifications\VendorApplicationApproved',
            'App\Notifications\LowStockAlert',
            'App\Notifications\NewOrderReceived',
            'App\Notifications\ReviewSubmitted',
        ];

        $type = $this->faker->randomElement($types);
        $notificationData = $this->getNotificationData($type);

        return [
            'id' => Str::uuid(),
            'type' => $type,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => User::factory(),
            'data' => json_encode($notificationData),
            'read_at' => $this->faker->optional(0.3)->dateTimeThisMonth(),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear(),
        ];
    }

    private function getNotificationData(string $type): array
    {
        $baseData = [
            'message' => $this->faker->sentence(),
            'action_url' => $this->faker->url(),
        ];

        switch ($type) {
            case 'App\Notifications\OrderConfirmed':
                return array_merge($baseData, [
                    'order_id' => Str::uuid(),
                    'order_number' => 'ORD' . $this->faker->numberBetween(100000, 999999),
                    'type' => 'order_confirmed',
                    'message' => 'Your order #ORD' . $this->faker->numberBetween(100000, 999999) . ' has been confirmed and is being processed.',
                    'action_url' => '/orders/' . Str::uuid(),
                ]);

            case 'App\Notifications\OrderShipped':
                return array_merge($baseData, [
                    'order_id' => Str::uuid(),
                    'order_number' => 'ORD' . $this->faker->numberBetween(100000, 999999),
                    'tracking_number' => 'TRK' . $this->faker->numberBetween(1000000000, 9999999999),
                    'type' => 'order_shipped',
                    'message' => 'Your order #ORD' . $this->faker->numberBetween(100000, 999999) . ' has been shipped with tracking number TRK' . $this->faker->numberBetween(1000000000, 9999999999),
                    'action_url' => '/orders/' . Str::uuid(),
                ]);

            case 'App\Notifications\OrderDelivered':
                return array_merge($baseData, [
                    'order_id' => Str::uuid(),
                    'order_number' => 'ORD' . $this->faker->numberBetween(100000, 999999),
                    'type' => 'order_delivered',
                    'message' => 'Your order #ORD' . $this->faker->numberBetween(100000, 999999) . ' has been delivered successfully.',
                    'action_url' => '/orders/' . Str::uuid(),
                ]);

            case 'App\Notifications\PaymentReceived':
                return array_merge($baseData, [
                    'payment_id' => Str::uuid(),
                    'order_id' => Str::uuid(),
                    'amount' => $this->faker->randomFloat(2, 10, 1000),
                    'type' => 'payment_received',
                    'message' => 'Payment of $' . $this->faker->randomFloat(2, 10, 1000) . ' has been received for order #ORD' . $this->faker->numberBetween(100000, 999999),
                    'action_url' => '/vendor/payments/' . Str::uuid(),
                ]);

            case 'App\Notifications\ProductOutOfStock':
                return array_merge($baseData, [
                    'product_id' => Str::uuid(),
                    'product_name' => $this->faker->words(3, true),
                    'type' => 'product_out_of_stock',
                    'message' => 'Product "' . $this->faker->words(3, true) . '" is out of stock.',
                    'action_url' => '/vendor/products/' . Str::uuid(),
                ]);

            case 'App\Notifications\LowStockAlert':
                return array_merge($baseData, [
                    'product_id' => Str::uuid(),
                    'product_name' => $this->faker->words(3, true),
                    'current_stock' => $this->faker->numberBetween(1, 10),
                    'type' => 'low_stock_alert',
                    'message' => 'Product "' . $this->faker->words(3, true) . '" is running low on stock. Only ' . $this->faker->numberBetween(1, 10) . ' items left.',
                    'action_url' => '/vendor/products/' . Str::uuid(),
                ]);

            case 'App\Notifications\NewOrderReceived':
                return array_merge($baseData, [
                    'order_id' => Str::uuid(),
                    'order_number' => 'ORD' . $this->faker->numberBetween(100000, 999999),
                    'type' => 'new_order_received',
                    'message' => 'New order #ORD' . $this->faker->numberBetween(100000, 999999) . ' has been received.',
                    'action_url' => '/vendor/orders/' . Str::uuid(),
                ]);

            case 'App\Notifications\NewMessage':
                return array_merge($baseData, [
                    'ticket_id' => Str::uuid(),
                    'type' => 'new_message',
                    'message' => 'You have a new message in your support ticket.',
                    'action_url' => '/support/tickets/' . Str::uuid(),
                ]);

            case 'App\Notifications\VendorApplicationApproved':
                return array_merge($baseData, [
                    'type' => 'vendor_application_approved',
                    'message' => 'Your vendor application has been approved! You can now start adding products.',
                    'action_url' => '/vendor/dashboard',
                ]);

            case 'App\Notifications\ReviewSubmitted':
                return array_merge($baseData, [
                    'product_id' => Str::uuid(),
                    'product_name' => $this->faker->words(3, true),
                    'rating' => $this->faker->numberBetween(1, 5),
                    'type' => 'review_submitted',
                    'message' => 'A new ' . $this->faker->numberBetween(1, 5) . '-star review has been submitted for your product "' . $this->faker->words(3, true) . '"',
                    'action_url' => '/vendor/products/' . Str::uuid() . '/reviews',
                ]);

            default:
                return $baseData;
        }
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => null,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => $this->faker->dateTimeThisMonth(),
        ]);
    }

    public function forCustomer(): static
    {
        $customerTypes = [
            'App\Notifications\OrderConfirmed',
            'App\Notifications\OrderShipped',
            'App\Notifications\OrderDelivered',
        ];

        return $this->state(fn (array $attributes) => [
            'type' => $this->faker->randomElement($customerTypes),
        ]);
    }

    public function forVendor(): static
    {
        $vendorTypes = [
            'App\Notifications\PaymentReceived',
            'App\Notifications\ProductOutOfStock',
            'App\Notifications\LowStockAlert',
            'App\Notifications\NewOrderReceived',
            'App\Notifications\ReviewSubmitted',
        ];

        return $this->state(fn (array $attributes) => [
            'type' => $this->faker->randomElement($vendorTypes),
        ]);
    }

    public function forAdmin(): static
    {
        $adminTypes = [
            'App\Notifications\VendorApplicationApproved',
            'App\Notifications\NewMessage',
        ];

        return $this->state(fn (array $attributes) => [
            'type' => $this->faker->randomElement($adminTypes),
        ]);
    }
}