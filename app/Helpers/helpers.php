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

// Payout Status Badge Colors
if (!function_exists('getPayoutStatusBadge')) {
    function getPayoutStatusBadge($status)
    {
        return match($status) {
            'pending' => 'warning',
            'processed' => 'success',
            'failed' => 'danger',
            default => 'secondary'
        };
    }
}

// Vendor Status Badge Colors
if (!function_exists('getVendorStatusBadge')) {
    function getVendorStatusBadge($status)
    {
        return match($status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }
}

// Review Status Badge Colors
if (!function_exists('getReviewStatusBadge')) {
    function getReviewStatusBadge($status)
    {
        return match($status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }
}

// Format currency
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return '₹' . number_format($amount, 2);
    }
}

// Get status label
if (!function_exists('getStatusLabel')) {
    function getStatusLabel($status)
    {
        return ucfirst($status);
    }
}

// Format date for display
if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'M d, Y H:i')
    {
        return $date ? $date->format($format) : 'N/A';
    }
}

// Truncate text with ellipsis
if (!function_exists('truncateText')) {
    function truncateText($text, $length = 50)
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }
}

// Get stock status badge
if (!function_exists('getStockStatusBadge')) {
    function getStockStatusBadge($status)
    {
        return match($status) {
            'in_stock' => 'success',
            'out_of_stock' => 'danger',
            'backorder' => 'warning',
            default => 'secondary'
        };
    }
}

// Get stock status label
if (!function_exists('getStockStatusLabel')) {
    function getStockStatusLabel($status)
    {
        return match($status) {
            'in_stock' => 'In Stock',
            'out_of_stock' => 'Out of Stock',
            'backorder' => 'Backorder',
            default => 'Unknown'
        };
    }
}

// Get user role badge
if (!function_exists('getUserRoleBadge')) {
    function getUserRoleBadge($role)
    {
        return match($role) {
            'admin' => 'danger',
            'vendor' => 'warning',
            'customer' => 'info',
            default => 'secondary'
        };
    }
}

// Check if route is active
if (!function_exists('isActiveRoute')) {
    function isActiveRoute($route, $output = 'active')
    {
        if (request()->is($route) || request()->is($route . '/*')) {
            return $output;
        }
        return '';
    }
}

// Ticket Status Badge Colors
if (!function_exists('getTicketStatusBadge')) {
    function getTicketStatusBadge($status)
    {
        return match($status) {
            'open' => 'warning',
            'in_progress' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'secondary'
        };
    }
}

// Priority Badge Colors
if (!function_exists('getPriorityBadge')) {
    function getPriorityBadge($priority)
    {
        return match($priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            'low' => 'success',
            default => 'secondary'
        };
    }
}

// Priority Icons
if (!function_exists('getPriorityIcon')) {
    function getPriorityIcon($priority)
    {
        return match($priority) {
            'urgent' => 'fa-exclamation-triangle',
            'high' => 'fa-exclamation-circle',
            'medium' => 'fa-info-circle',
            'low' => 'fa-flag',
            default => 'fa-flag'
        };
    }
}