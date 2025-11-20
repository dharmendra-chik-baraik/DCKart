<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_status' => 'sometimes|string|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'sometimes|string|in:pending,completed,failed,refunded',
            'shipping_charge' => 'sometimes|numeric|min:0',
            'discount' => 'sometimes|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ];
    }
}