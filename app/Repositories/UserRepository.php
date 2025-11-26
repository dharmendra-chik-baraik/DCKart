<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers(array $filters = []): LengthAwarePaginator
    {
        $query = User::with(['vendorProfile']);

        // Apply filters
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(20);
    }

    public function getUserById(string $id): ?User
    {
        return User::with(['vendorProfile', 'addresses'])->find($id);
    }

    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => bcrypt($data['password']),
                'role' => $data['role'] ?? 'customer',
                'status' => $data['status'] ?? 'active',
                'email_verified_at' => $data['email_verified'] ? now() : null,
            ]);
        });
    }

    public function updateUser(string $id, array $data): bool
    {
        $user = $this->getUserById($id);
        
        if (!$user) {
            return false;
        }

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $user->update($data);
    }

    public function deleteUser(string $id): bool
    {
        $user = $this->getUserById($id);
        
        if (!$user) {
            return false;
        }

        return $user->delete();
    }

    public function changeUserStatus(string $id, string $status): bool
    {
        $user = $this->getUserById($id);
        
        if (!$user) {
            return false;
        }

        return $user->update(['status' => $status]);
    }

    public function getUsersByRole(string $role): LengthAwarePaginator
    {
        return User::where('role', $role)
            ->with(['vendorProfile'])
            ->latest()
            ->paginate(20);
    }
}