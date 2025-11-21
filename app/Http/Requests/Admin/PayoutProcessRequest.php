<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PayoutProcessRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|in:pending,processed,failed',
            'transaction_id' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'Payout status is required',
            'status.in' => 'Invalid payout status',
        ];
    }
}