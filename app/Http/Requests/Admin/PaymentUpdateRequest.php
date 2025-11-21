<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PaymentUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_status' => 'required|in:pending,completed,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'payment_status.required' => 'Payment status is required',
            'payment_status.in' => 'Invalid payment status',
        ];
    }
}