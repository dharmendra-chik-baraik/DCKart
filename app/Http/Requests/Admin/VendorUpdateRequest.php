<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\VendorProfile;

class VendorUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vendor = VendorProfile::find($this->route('id'));

        return [
            // User fields
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($vendor->user_id ?? ''),
            'password' => 'nullable|min:8|confirmed',
            'user_status' => 'required|in:active,inactive,suspended',
            
            // Vendor fields
            'shop_name' => 'required|string|max:255',
            'shop_slug' => 'required|string|max:255|unique:vendor_profiles,shop_slug,' . $this->route('id'),
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'pincode' => 'nullable|string',
            'vendor_status' => 'required|in:pending,approved,suspended,rejected',
            'is_verified' => 'boolean',
        ];
    }
}