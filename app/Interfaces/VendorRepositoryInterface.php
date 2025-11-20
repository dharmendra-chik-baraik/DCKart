<?php
// App/Interfaces/VendorRepositoryInterface.php
namespace App\Interfaces;

use App\Models\VendorProfile;
use Illuminate\Pagination\LengthAwarePaginator;

interface VendorRepositoryInterface
{
    public function getAllVendors(array $filters = []): LengthAwarePaginator;
    public function getVendorById(string $id): ?VendorProfile;
    public function createVendor(array $data): VendorProfile;
    public function updateVendor(string $id, array $data): bool;
    public function deleteVendor(string $id): bool;
    public function changeVendorStatus(string $id, string $status): bool;
    public function getVendorStats(string $id): array;
}