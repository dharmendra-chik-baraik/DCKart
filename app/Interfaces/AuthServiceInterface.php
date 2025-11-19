<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function login(array $credentials, bool $remember = false);
    public function logout();
    public function getAuthenticatedUser();
}