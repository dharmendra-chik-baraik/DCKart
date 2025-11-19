<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Standard Shipping',
                'price' => 50.00,
                'delivery_time' => '5-7 business days',
                'status' => true,
            ],
            [
                'name' => 'Express Shipping',
                'price' => 150.00,
                'delivery_time' => '2-3 business days',
                'status' => true,
            ],
            [
                'name' => 'Overnight Shipping',
                'price' => 300.00,
                'delivery_time' => 'Next business day',
                'status' => true,
            ],
            [
                'name' => 'Free Shipping',
                'price' => 0.00,
                'delivery_time' => '7-10 business days',
                'status' => true,
            ],
        ];

        foreach ($methods as $method) {
            ShippingMethod::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $method['name'],
                'price' => $method['price'],
                'delivery_time' => $method['delivery_time'],
                'status' => $method['status'],
            ]);
        }

        echo "Shipping methods seeded: " . ShippingMethod::count() . "\n";
    }
}