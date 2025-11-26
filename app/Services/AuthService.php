<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function getAuthenticatedUser()
    {
        return Auth::user();
    }

    public function registerUser(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
            'status' => 'active',
            'email_verified' => null,
        ];

        return $this->userRepository->createUser($userData);
    }
}