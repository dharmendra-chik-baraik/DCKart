<?php

// Order Status Badge Colors
if (!function_exists('getOrderStatusBadge')) {
    function getOrderStatusBadge($status)
    {
        return match($status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }
}

// Payment Status Badge Colors
if (!function_exists('getPaymentStatusBadge')) {
    function getPaymentStatusBadge($status)
    {
        return match($status) {
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'refunded' => 'secondary',
            default => 'secondary'
        };
    }
}

// Format currency
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return '$' . number_format($amount, 2);
    }
}

// Get status label
if (!function_exists('getStatusLabel')) {
    function getStatusLabel($status)
    {
        return ucfirst($status);
    }
}