<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InventoryUpdateRequest;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'stock_status', 'vendor_id']);
        
        $products = $this->inventoryService->getInventoryProducts($filters);
        $stats = $this->inventoryService->getInventoryStatistics();

        return view('admin.inventory.index', compact('products', 'stats', 'filters'));
    }

    public function lowStock()
    {
        $products = $this->inventoryService->getLowStockProducts();
        $stats = $this->inventoryService->getInventoryStatistics();

        return view('admin.inventory.low-stock', compact('products', 'stats'));
    }

    public function outOfStock()
    {
        $products = $this->inventoryService->getOutOfStockProducts();
        $stats = $this->inventoryService->getInventoryStatistics();

        return view('admin.inventory.out-of-stock', compact('products', 'stats'));
    }

    public function update(InventoryUpdateRequest $request, $productId)
    {
        try {
            $this->inventoryService->updateProductStock($productId, $request->validated());
            
            return redirect()->back()->with('success', 'Inventory updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update inventory: ' . $e->getMessage());
        }
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.stock' => 'required|integer|min:0'
        ]);

        try {
            $this->inventoryService->bulkUpdateStock($request->products);
            
            return redirect()->back()->with('success', 'Bulk inventory update completed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bulk update failed: ' . $e->getMessage());
        }
    }

    public function updateStockStatus(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'status' => 'required|in:in_stock,out_of_stock,backorder'
        ]);

        try {
            $this->inventoryService->updateStockStatus($request->product_id, $request->status);
            
            return response()->json(['success' => true, 'message' => 'Stock status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update stock status.'], 500);
        }
    }
}