<?php

namespace App\Interfaces;

interface InventoryRepositoryInterface
{
    public function getAllProducts($filters = []);
    public function getLowStockProducts($threshold = 10);
    public function getOutOfStockProducts();
    public function updateStock($productId, $stockData);
    public function bulkUpdateStock($productsData);
    public function updateStockStatus($productId, $status);
    public function getInventoryStats();
}