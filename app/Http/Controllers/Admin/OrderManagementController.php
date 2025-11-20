<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderIndexRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateStatusRequest;
use App\Interfaces\OrderServiceInterface; 
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderManagementController extends Controller
{
    public function __construct(private OrderServiceInterface $orderService)
    {
    }

    public function index(OrderIndexRequest $request): View
    {
        $orders = $this->orderService->getOrders($request->validated());
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show(string $id): View
    {
        $order = $this->orderService->getOrder($id);
        
        if (!$order) {
            abort(404, 'Order not found');
        }

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(string $id, OrderUpdateStatusRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->updateOrderStatus(
                $id, 
                $request->status, 
                $request->note,
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(string $id, OrderUpdateRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->updateOrder($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function cancel(string $id, Request $request): JsonResponse
    {
        try {
            $order = $this->orderService->cancelOrder($id, $request->note);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function statistics(Request $request): View
    {
        $stats = $this->orderService->getOrderStatistics($request->all());
        
        return view('admin.orders.statistics', compact('stats'));
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->orderService->deleteOrder($id);

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}