<?php

namespace App\Services\Frontend;

use App\Interfaces\Frontend\ProductRepositoryInterface;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts($filters = [], $sort = 'latest', $perPage = 12)
    {
        return $this->productRepository->getAllProducts($filters, $sort, $perPage);
    }

    public function getProduct($slug)
    {
        return $this->productRepository->getProductBySlug($slug);
    }

    public function searchProducts($query, $perPage = 12)
    {
        return $this->productRepository->searchProducts($query, $perPage);
    }

    public function getProductsByCategory($categoryId, $perPage = 12)
    {
        return $this->productRepository->getProductsByCategory($categoryId, $perPage);
    }

    public function getRelatedProducts($productId, $categoryId, $limit = 4)
    {
        return $this->productRepository->getRelatedProducts($productId, $categoryId, $limit);
    }
}