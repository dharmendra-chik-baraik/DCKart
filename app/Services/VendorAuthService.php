<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VendorAuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function registerVendor(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'vendor',
            'status' => 'active',
            'shop_name' => $data['shop_name'],
            'shop_address' => $data['shop_address'],
            'shop_city' => $data['shop_city'],
            'shop_state' => $data['shop_state'],
            'shop_pincode' => $data['shop_pincode'],
            'gst_number' => $data['gst_number'] ?? null,
            'pan_number' => $data['pan_number'] ?? null,
        ];

        return $this->userRepository->createUser($userData);
    }
}