<?php

namespace App\Interfaces\Frontend;

interface ProductRepositoryInterface
{
    public function getAllProducts($filters = [], $sort = 'latest', $perPage = 12);
    public function getProductBySlug($slug);
    public function searchProducts($query, $perPage = 12);
    public function getProductsByCategory($categoryId, $perPage = 12);
    public function getRelatedProducts($productId, $categoryId, $limit = 4);
}