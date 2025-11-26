<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class VendorRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_address' => ['required', 'string'],
            'shop_city' => ['required', 'string', 'max:255'],
            'shop_state' => ['required', 'string', 'max:255'],
            'shop_pincode' => ['required', 'string', 'max:10'],
            'gst_number' => ['nullable', 'string', 'max:15'],
            'pan_number' => ['nullable', 'string', 'max:10'],
            'agree_terms' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.unique' => 'This phone number is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'shop_name.required' => 'Shop name is required.',
            'shop_address.required' => 'Shop address is required.',
            'shop_city.required' => 'City is required.',
            'shop_state.required' => 'State is required.',
            'shop_pincode.required' => 'Pincode is required.',
            'agree_terms.required' => 'You must agree to the vendor terms and conditions.',
        ];
    }
}