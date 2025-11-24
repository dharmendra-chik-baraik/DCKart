<?php

namespace App\Helpers;

use App\Events\ActivityLogged;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityHelper
{
    /**
     * Log an activity
     */
    public static function log(string $action, string $module, ?string $description = null, ?User $user = null): void
    {
        $user = $user ?: Auth::user();
        
        if ($user) {
            event(new ActivityLogged($user, $action, $module, $description));
        }
    }

    /**
     * Log product-related activities
     */
    public static function logProduct(string $action, $product, ?string $additionalInfo = null): void
    {
        $description = ucfirst($action) . " product: {$product->name} (ID: {$product->id})";
        
        if ($additionalInfo) {
            $description .= " - {$additionalInfo}";
        }

        self::log($action, 'product', $description);
    }

    /**
     * Log user-related activities
     */
    public static function logUser(string $action, $user, ?string $additionalInfo = null): void
    {
        $targetUserName = $user->name ?? 'Unknown User';
        $description = ucfirst($action) . " user: {$targetUserName} (ID: {$user->id})";
        
        if ($additionalInfo) {
            $description .= " - {$additionalInfo}";
        }

        self::log($action, 'user', $description);
    }

    /**
     * Log category-related activities
     */
    public static function logCategory(string $action, $category, ?string $additionalInfo = null): void
    {
        $description = ucfirst($action) . " category: {$category->name} (ID: {$category->id})";
        
        if ($additionalInfo) {
            $description .= " - {$additionalInfo}";
        }

        self::log($action, 'category', $description);
    }

    /**
     * Log vendor-related activities
     */
    public static function logVendor(string $action, $vendor, ?string $additionalInfo = null): void
    {
        $description = ucfirst($action) . " vendor: {$vendor->shop_name} (ID: {$vendor->id})";
        
        if ($additionalInfo) {
            $description .= " - {$additionalInfo}";
        }

        self::log($action, 'vendor', $description);
    }

    /**
     * Log order-related activities
     */
    public static function logOrder(string $action, $order, ?string $additionalInfo = null): void
    {
        $description = ucfirst($action) . " order: #{$order->order_number}";
        
        if ($additionalInfo) {
            $description .= " - {$additionalInfo}";
        }

        self::log($action, 'order', $description);
    }

    /**
     * Log page-related activities
     */
    public static function logPage(string $action, $page, ?string $additionalInfo = null): void
    {
        $description = ucfirst($action) . " page: {$page->title} (ID: {$page->id})";
        
        if ($additionalInfo) {
            $description .= " - {$additionalInfo}";
        }

        self::log($action, 'page', $description);
    }
}