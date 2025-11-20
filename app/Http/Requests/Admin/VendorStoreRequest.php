<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VendorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // User fields
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'user_status' => 'required|in:active,inactive,suspended', // User status
            
            // Vendor fields
            'shop_name' => 'required|string|max:255',
            'shop_slug' => 'required|string|max:255|unique:vendor_profiles,shop_slug',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'pincode' => 'nullable|string',
            'vendor_status' => 'required|in:pending,approved,suspended,rejected', // Vendor status
            'is_verified' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_status.required' => 'Please select user account status.',
            'vendor_status.required' => 'Please select vendor business status.',
        ];
    }
}