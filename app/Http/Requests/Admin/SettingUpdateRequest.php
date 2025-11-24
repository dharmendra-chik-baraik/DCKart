<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'site_phone' => 'nullable|string|max:20',
            'site_currency' => 'required|string|size:3',
            'site_timezone' => 'required|string|max:50',
            'maintenance_mode' => 'boolean',
            
            // Ecommerce
            'currency_symbol' => 'required|string|max:5',
            'currency_position' => 'required|in:left,right',
            'decimal_points' => 'required|integer|min:0|max:4',
            'low_stock_threshold' => 'required|integer|min:0',
            'weight_unit' => 'required|string|max:10',
            'dimension_unit' => 'required|string|max:10',
            
            // Vendor
            'vendor_registration' => 'boolean',
            'vendor_auto_approval' => 'boolean',
            'vendor_commission_rate' => 'required|numeric|min:0|max:100',
            'vendor_payout_days' => 'required|integer|min:1|max:30',
            
            // Order
            'order_auto_confirm' => 'boolean',
            'order_cancellation_time' => 'required|integer|min:1|max:168',
            'free_shipping_min_amount' => 'required|numeric|min:0',
            
            // Payment
            'default_payment_method' => 'required|string|max:50',
            'payment_test_mode' => 'boolean',
            
            // Tax
            'tax_enabled' => 'boolean',
            'tax_rate' => 'required|numeric|min:0|max:100',
            
            // Email
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
            'customer_order_notifications' => 'boolean',
            'vendor_order_notifications' => 'boolean',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'site_name.required' => 'Site name is required.',
            'site_email.required' => 'Site email is required.',
            'site_email.email' => 'Please enter a valid email address.',
            'vendor_commission_rate.numeric' => 'Commission rate must be a number.',
            'vendor_commission_rate.min' => 'Commission rate cannot be negative.',
            'vendor_commission_rate.max' => 'Commission rate cannot exceed 100%.',
            'tax_rate.numeric' => 'Tax rate must be a number.',
            'tax_rate.min' => 'Tax rate cannot be negative.',
            'tax_rate.max' => 'Tax rate cannot exceed 100%.',
        ];
    }
}