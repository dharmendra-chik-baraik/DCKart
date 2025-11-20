<?php

namespace App\Repositories;

use App\Interfaces\VendorRepositoryInterface;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class VendorRepository implements VendorRepositoryInterface
{
    public function getAllVendors(array $filters = []): LengthAwarePaginator
    {
        $query = VendorProfile::with(['user', 'products']);

        // Apply filters - updated to match your schema
        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('shop_name', 'like', '%'.$filters['search'].'%')
                    ->orWhere('shop_slug', 'like', '%'.$filters['search'].'%')
                    ->orWhereHas('user', function ($userQuery) use ($filters) {
                        $userQuery->where('name', 'like', '%'.$filters['search'].'%')
                            ->orWhere('email', 'like', '%'.$filters['search'].'%');
                    });
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['verified'])) {
            if ($filters['verified'] == 'yes') {
                $query->whereNotNull('verified_at');
            } else {
                $query->whereNull('verified_at');
            }
        }

        return $query->latest()->paginate(20);
    }

    public function getVendorById(string $id): ?VendorProfile
    {
        return VendorProfile::with(['user', 'products'])->find($id);
    }

    public function createVendor(array $data): VendorProfile
    {
        return DB::transaction(function () use ($data) {
            // Create user with user status
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role' => 'vendor',
                'status' => $data['user_status'] ?? 'active', // User status
                'email_verified_at' => now(),
            ]);

            // Create vendor profile with vendor status
            return VendorProfile::create([
                'user_id' => $user->id,
                'shop_name' => $data['shop_name'],
                'shop_slug' => $data['shop_slug'],
                'description' => $data['description'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'country' => $data['country'] ?? null,
                'pincode' => $data['pincode'] ?? null,
                'status' => $data['vendor_status'] ?? 'pending', // Vendor status
                'verified_at' => $data['is_verified'] ? now() : null,
            ]);
        });
    }

    public function updateVendor(string $id, array $data): bool
    {
        $vendor = $this->getVendorById($id);

        if (! $vendor) {
            return false;
        }

        return DB::transaction(function () use ($vendor, $data) {
            // Update user with user status
            $userData = [];
            if (isset($data['name'])) {
                $userData['name'] = $data['name'];
            }
            if (isset($data['email'])) {
                $userData['email'] = $data['email'];
            }
            if (isset($data['user_status'])) {
                $userData['status'] = $data['user_status'];
            }

            if (! empty($userData)) {
                $vendor->user->update($userData);
            }

            // Update vendor profile with vendor status
            $vendorData = [
                'shop_name' => $data['shop_name'] ?? $vendor->shop_name,
                'shop_slug' => $data['shop_slug'] ?? $vendor->shop_slug,
                'description' => $data['description'] ?? $vendor->description,
                'phone' => $data['phone'] ?? $vendor->phone,
                'address' => $data['address'] ?? $vendor->address,
                'city' => $data['city'] ?? $vendor->city,
                'state' => $data['state'] ?? $vendor->state,
                'country' => $data['country'] ?? $vendor->country,
                'pincode' => $data['pincode'] ?? $vendor->pincode,
                'status' => $data['vendor_status'] ?? $vendor->status,
            ];

            // Handle verification
            if (isset($data['is_verified'])) {
                $vendorData['verified_at'] = $data['is_verified'] ? now() : null;
            }

            return $vendor->update($vendorData);
        });
    }

    public function deleteVendor(string $id): bool
    {
        $vendor = $this->getVendorById($id);

        if (! $vendor) {
            return false;
        }

        return DB::transaction(function () use ($vendor) {
            // Delete vendor profile and user
            $userId = $vendor->user_id;
            $vendor->delete();
            User::find($userId)->delete();

            return true;
        });
    }

    public function changeVendorStatus(string $id, string $status): bool
    {
        $vendor = $this->getVendorById($id);

        if (! $vendor) {
            return false;
        }

        // Update both vendor profile and user status
        $vendor->user->update(['status' => $status]);

        return $vendor->update(['status' => $status]);
    }

    public function getVendorStats(string $id): array
    {
        $vendor = $this->getVendorById($id);

        if (! $vendor) {
            return [];
        }

        // Use basic counts for now
        return [
            'total_products' => $vendor->products->count(),
            'total_orders' => $vendor->orders->count(),
            'total_revenue' => $vendor->orders->sum('total_amount'),
            'pending_orders' => $vendor->orders->where('status', 'pending')->count(),
        ];
    }

    public function toggleVerification(string $id): bool
    {
        $vendor = $this->getVendorById($id);

        if (! $vendor) {
            return false;
        }

        if ($vendor->verified_at) {
            return $vendor->update(['verified_at' => null]);
        } else {
            return $vendor->update(['verified_at' => now()]);
        }
    }
}
