<?php

namespace App\Services\Frontend;

use App\Interfaces\Frontend\CategoryRepositoryInterface;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function getCategoryWithProducts($slug, $perPage = 12)
    {
        return $this->categoryRepository->getCategoryWithProducts($slug, $perPage);
    }
}