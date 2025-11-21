<?php

namespace App\Repositories;

use App\Interfaces\InventoryRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function getAllProducts($filters = [])
    {
        $query = Product::with(['vendor', 'category']);
        
        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sku', 'like', '%' . $filters['search'] . '%');
        }
        
        if (isset($filters['stock_status']) && $filters['stock_status']) {
            $query->where('stock_status', $filters['stock_status']);
        }
        
        if (isset($filters['vendor_id']) && $filters['vendor_id']) {
            $query->where('vendor_id', $filters['vendor_id']);
        }
        
        // Order by stock (lowest first)
        $query->orderBy('stock', 'asc')->orderBy('name', 'asc');
        
        return $query->paginate(20);
    }

    public function getLowStockProducts($threshold = 10)
    {
        return Product::with(['vendor', 'category'])
            ->where('stock', '<', $threshold)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->paginate(20);
    }

    public function getOutOfStockProducts()
    {
        return Product::with(['vendor', 'category'])
            ->where('stock', 0)
            ->orderBy('name', 'asc')
            ->paginate(20);
    }

    public function updateStock($productId, $stockData)
    {
        $product = Product::findOrFail($productId);
        
        $product->update([
            'stock' => $stockData['stock'],
            'stock_status' => $stockData['stock'] > 0 ? 'in_stock' : 'out_of_stock'
        ]);
        
        return $product;
    }

    public function bulkUpdateStock($productsData)
    {
        return DB::transaction(function () use ($productsData) {
            $updatedProducts = [];
            
            foreach ($productsData as $productData) {
                $product = Product::find($productData['id']);
                if ($product) {
                    $product->update([
                        'stock' => $productData['stock'],
                        'stock_status' => $productData['stock'] > 0 ? 'in_stock' : 'out_of_stock'
                    ]);
                    $updatedProducts[] = $product;
                }
            }
            
            return $updatedProducts;
        });
    }

    public function updateStockStatus($productId, $status)
    {
        $product = Product::findOrFail($productId);
        $product->update(['stock_status' => $status]);
        return $product;
    }

    public function getInventoryStats()
    {
        return [
            'total_products' => Product::count(),
            'in_stock' => Product::where('stock_status', 'in_stock')->count(),
            'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
            'low_stock' => Product::where('stock', '<', 10)->where('stock', '>', 0)->count(),
            'backorder' => Product::where('stock_status', 'backorder')->count(),
        ];
    }
}