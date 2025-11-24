<?php

namespace App\Interfaces\Frontend;

interface SearchRepositoryInterface
{
    public function searchProducts($query, $perPage = 12);
    public function searchCategories($query, $limit = 5);
    public function searchVendors($query, $limit = 5);
}