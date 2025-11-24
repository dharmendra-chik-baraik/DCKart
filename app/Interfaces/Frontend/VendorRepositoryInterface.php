<?php

namespace App\Interfaces\Frontend;

interface VendorRepositoryInterface
{
    public function getAllVendors($perPage = 12);
    public function getVendorBySlug($slug);
    public function getVendorProducts($vendorId, $perPage = 12);
}