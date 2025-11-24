<?php

namespace App\Services\Frontend;

use App\Interfaces\Frontend\VendorRepositoryInterface;

class VendorService
{
    protected $vendorRepository;

    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    public function getAllVendors($perPage = 12)
    {
        return $this->vendorRepository->getAllVendors($perPage);
    }

    public function getVendorWithProducts($slug, $perPage = 12)
    {
        $vendor = $this->vendorRepository->getVendorBySlug($slug);
        $products = $this->vendorRepository->getVendorProducts($vendor->id, $perPage);
        
        return [
            'vendor' => $vendor,
            'products' => $products
        ];
    }
}