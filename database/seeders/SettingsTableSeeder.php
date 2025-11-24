<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing settings
        DB::table('settings')->truncate();

        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'DCKart',
                'type' => 'string',
                'group' => 'general',
                'description' => 'The name of your ecommerce platform'
            ],
            [
                'key' => 'site_email',
                'value' => 'admin@dckart.com',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default email address for system communications'
            ],
            [
                'key' => 'site_phone',
                'value' => '+91 1234567890',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Customer support phone number'
            ],
            [
                'key' => 'site_currency',
                'value' => 'INR',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default currency code'
            ],
            [
                'key' => 'site_timezone',
                'value' => 'Asia/Kolkata',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default timezone'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Enable maintenance mode'
            ],

            // Ecommerce Settings
            [
                'key' => 'currency_symbol',
                'value' => '₹',
                'type' => 'string',
                'group' => 'ecommerce',
                'description' => 'Currency symbol'
            ],
            [
                'key' => 'currency_position',
                'value' => 'left',
                'type' => 'string',
                'group' => 'ecommerce',
                'description' => 'Position of currency symbol (left/right)'
            ],
            [
                'key' => 'decimal_points',
                'value' => '2',
                'type' => 'integer',
                'group' => 'ecommerce',
                'description' => 'Number of decimal points for prices'
            ],
            [
                'key' => 'low_stock_threshold',
                'value' => '10',
                'type' => 'integer',
                'group' => 'ecommerce',
                'description' => 'Low stock alert threshold'
            ],
            [
                'key' => 'weight_unit',
                'value' => 'kg',
                'type' => 'string',
                'group' => 'ecommerce',
                'description' => 'Default weight unit'
            ],
            [
                'key' => 'dimension_unit',
                'value' => 'cm',
                'type' => 'string',
                'group' => 'ecommerce',
                'description' => 'Default dimension unit'
            ],

            // Vendor Settings
            [
                'key' => 'vendor_registration',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'vendor',
                'description' => 'Allow new vendor registrations'
            ],
            [
                'key' => 'vendor_auto_approval',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'vendor',
                'description' => 'Automatically approve new vendors'
            ],
            [
                'key' => 'vendor_commission_rate',
                'value' => '10.00',
                'type' => 'string',
                'group' => 'vendor',
                'description' => 'Default commission rate for vendors (%)'
            ],
            [
                'key' => 'vendor_payout_days',
                'value' => '7',
                'type' => 'integer',
                'group' => 'vendor',
                'description' => 'Days to process vendor payouts'
            ],

            // Order Settings
            [
                'key' => 'order_auto_confirm',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'order',
                'description' => 'Automatically confirm orders'
            ],
            [
                'key' => 'order_cancellation_time',
                'value' => '24',
                'type' => 'integer',
                'group' => 'order',
                'description' => 'Hours allowed for order cancellation'
            ],
            [
                'key' => 'free_shipping_min_amount',
                'value' => '1000.00',
                'type' => 'string',
                'group' => 'order',
                'description' => 'Minimum amount for free shipping'
            ],

            // Payment Settings
            [
                'key' => 'payment_gateways',
                'value' => '["cash_on_delivery","stripe","razorpay"]',
                'type' => 'json',
                'group' => 'payment',
                'description' => 'Available payment gateways'
            ],
            [
                'key' => 'default_payment_method',
                'value' => 'cash_on_delivery',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Default payment method'
            ],
            [
                'key' => 'payment_test_mode',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Enable payment test mode'
            ],

            // Tax Settings
            [
                'key' => 'tax_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'tax',
                'description' => 'Enable tax calculation'
            ],
            [
                'key' => 'tax_rate',
                'value' => '18.00',
                'type' => 'string',
                'group' => 'tax',
                'description' => 'Default tax rate (%)'
            ],

            // Email Settings
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@dckart.com',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Default from email address'
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'DCKart',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Default from name'
            ],
            [
                'key' => 'customer_order_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Send order notifications to customers'
            ],
            [
                'key' => 'vendor_order_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Send order notifications to vendors'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}