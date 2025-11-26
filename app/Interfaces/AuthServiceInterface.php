<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function login(array $credentials, bool $remember = false): bool;
    public function logout(): void;
    public function getAuthenticatedUser();
    public function registerUser(array $data): User;
}