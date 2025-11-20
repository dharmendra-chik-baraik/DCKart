<?php
// App/Services/UserService.php
namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function getAllUsers(array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->getAllUsers($filters);
    }

    public function getUserById(string $id): ?User
    {
        return $this->userRepository->getUserById($id);
    }

    public function createUser(array $data): User
    {
        // Validate role
        $validRoles = ['admin', 'vendor', 'customer'];
        if (!in_array($data['role'], $validRoles)) {
            throw new \InvalidArgumentException('Invalid user role');
        }

        return $this->userRepository->createUser($data);
    }

    public function updateUser(string $id, array $data): bool
    {
        // Remove password if empty
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $this->userRepository->updateUser($id, $data);
    }

    public function deleteUser(string $id): bool
    {
        // Prevent admin from deleting themselves
        $user = $this->getUserById($id);
        if ($user && $user->id === auth()->id()) {
            throw new \Exception('You cannot delete your own account.');
        }

        return $this->userRepository->deleteUser($id);
    }

    public function changeUserStatus(string $id, string $status): bool
    {
        $validStatuses = ['active', 'inactive', 'suspended'];
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid status');
        }

        return $this->userRepository->changeUserStatus($id, $status);
    }

    public function getUsersByRole(string $role): LengthAwarePaginator
    {
        $validRoles = ['admin', 'vendor', 'customer'];
        if (!in_array($role, $validRoles)) {
            throw new \InvalidArgumentException('Invalid user role');
        }

        return $this->userRepository->getUsersByRole($role);
    }
}