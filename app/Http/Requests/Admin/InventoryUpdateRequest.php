<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InventoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'stock' => 'required|integer|min:0',
            'stock_status' => 'sometimes|in:in_stock,out_of_stock,backorder'
        ];
    }

    public function messages()
    {
        return [
            'stock.required' => 'Stock quantity is required',
            'stock.integer' => 'Stock must be a whole number',
            'stock.min' => 'Stock cannot be negative',
            'stock_status.in' => 'Invalid stock status'
        ];
    }
}