<?php

namespace App\Interfaces\Frontend;

interface CategoryRepositoryInterface
{
    public function getAllCategories();
    public function getCategoryBySlug($slug);
    public function getCategoryWithProducts($slug, $perPage = 12);
}