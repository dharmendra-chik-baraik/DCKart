<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'agree_terms' => ['required', 'accepted'],
        ];



        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.unique' => 'This phone number is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'agree_terms.required' => 'You must agree to the terms and conditions.',
            'agree_terms.accepted' => 'You must agree to the terms and conditions.',
        ];
    }
}