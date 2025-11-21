<?php

namespace App\Services;

use App\Interfaces\InventoryRepositoryInterface;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    protected $inventoryRepository;

    public function __construct(InventoryRepositoryInterface $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    public function getInventoryProducts($filters = [])
    {
        return $this->inventoryRepository->getAllProducts($filters);
    }

    public function getLowStockProducts($threshold = 10)
    {
        return $this->inventoryRepository->getLowStockProducts($threshold);
    }

    public function getOutOfStockProducts()
    {
        return $this->inventoryRepository->getOutOfStockProducts();
    }

    public function updateProductStock($productId, $stockData)
    {
        try {
            $product = $this->inventoryRepository->updateStock($productId, $stockData);
            
            // Log inventory change
            Log::info("Inventory updated for product {$product->name}", [
                'product_id' => $productId,
                'new_stock' => $stockData['stock'],
                'updated_by' => auth()->id()
            ]);
            
            return $product;
        } catch (\Exception $e) {
            Log::error("Failed to update inventory for product {$productId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function bulkUpdateStock($productsData)
    {
        try {
            $updatedProducts = $this->inventoryRepository->bulkUpdateStock($productsData);
            
            Log::info("Bulk inventory update completed", [
                'updated_count' => count($updatedProducts),
                'updated_by' => auth()->id()
            ]);
            
            return $updatedProducts;
        } catch (\Exception $e) {
            Log::error("Bulk inventory update failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateStockStatus($productId, $status)
    {
        return $this->inventoryRepository->updateStockStatus($productId, $status);
    }

    public function getInventoryStatistics()
    {
        return $this->inventoryRepository->getInventoryStats();
    }
}