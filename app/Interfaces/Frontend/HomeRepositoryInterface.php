<?php

namespace App\Interfaces\Frontend;

interface HomeRepositoryInterface
{
    public function getFeaturedProducts($limit = 8);
    public function getPopularCategories($limit = 8);
    public function getTopVendors($limit = 6);
    public function getActivePages();
}