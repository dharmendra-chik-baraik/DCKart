<?php
namespace App\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getAllUsers(array $filters = []): LengthAwarePaginator;
    public function getUserById(string $id): ?User;
    public function createUser(array $data): User;
    public function updateUser(string $id, array $data): bool;
    public function deleteUser(string $id): bool;
    public function changeUserStatus(string $id, string $status): bool;
    public function getUsersByRole(string $role): LengthAwarePaginator;
}