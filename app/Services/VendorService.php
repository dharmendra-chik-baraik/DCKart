<?php
namespace App\Services;

use App\Interfaces\VendorRepositoryInterface;
use App\Models\VendorProfile;
use Illuminate\Pagination\LengthAwarePaginator;

class VendorService
{
    public function __construct(protected VendorRepositoryInterface $vendorRepository) {}

    public function getAllVendors(array $filters = []): LengthAwarePaginator
    {
        return $this->vendorRepository->getAllVendors($filters);
    }

    public function getVendorById(string $id): ?VendorProfile
    {
        return $this->vendorRepository->getVendorById($id);
    }

    public function createVendor(array $data): VendorProfile
    {
        // Validate required fields
        $required = ['name', 'email', 'password', 'shop_name', 'shop_slug'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("The {$field} field is required.");
            }
        }

        // Check if shop slug is unique
        if (VendorProfile::where('shop_slug', $data['shop_slug'])->exists()) {
            throw new \InvalidArgumentException('The shop slug has already been taken.');
        }

        // Check if email is unique
        if (\App\Models\User::where('email', $data['email'])->exists()) {
            throw new \InvalidArgumentException('The email has already been taken.');
        }

        return $this->vendorRepository->createVendor($data);
    }

    public function updateVendor(string $id, array $data): bool
    {
        $vendor = $this->getVendorById($id);
        
        if (!$vendor) {
            throw new \InvalidArgumentException('Vendor not found.');
        }

        // Validate shop slug uniqueness
        if (isset($data['shop_slug']) && 
            VendorProfile::where('shop_slug', $data['shop_slug'])->where('id', '!=', $id)->exists()) {
            throw new \InvalidArgumentException('The shop slug has already been taken.');
        }

        // Validate email uniqueness
        if (isset($data['email']) && 
            \App\Models\User::where('email', $data['email'])->where('id', '!=', $vendor->user_id)->exists()) {
            throw new \InvalidArgumentException('The email has already been taken.');
        }

        // Remove password if empty
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }

        return $this->vendorRepository->updateVendor($id, $data);
    }

    public function deleteVendor(string $id): bool
    {
        $vendor = $this->getVendorById($id);
        
        if (!$vendor) {
            throw new \InvalidArgumentException('Vendor not found.');
        }

        return $this->vendorRepository->deleteVendor($id);
    }

    public function changeVendorStatus(string $id, string $status): bool
    {
        // Use your actual status values
        $validStatuses = ['approved', 'pending', 'suspended', 'rejected'];
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid status');
        }

        return $this->vendorRepository->changeVendorStatus($id, $status);
    }

    public function getVendorStats(string $id): array
    {
        return $this->vendorRepository->getVendorStats($id);
    }

    public function toggleVerification(string $id): bool
    {
        return $this->vendorRepository->toggleVerification($id);
    }

    // Helper method to check if vendor is verified
    public function isVerified(string $id): bool
    {
        $vendor = $this->getVendorById($id);
        return $vendor ? !is_null($vendor->verified_at) : false;
    }
}