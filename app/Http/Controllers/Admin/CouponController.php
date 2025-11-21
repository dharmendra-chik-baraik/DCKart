<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponStoreRequest;
use App\Http\Requests\Admin\CouponUpdateRequest;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'discount_type']);
        $coupons = $this->couponService->getAllCoupons($filters);
        $stats = $this->couponService->getCouponStats();

        return view('admin.coupons.index', compact('coupons', 'stats', 'filters'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(CouponStoreRequest $request)
    {
        try {
            $this->couponService->createCoupon($request->validated());
            
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create coupon: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $coupon = $this->couponService->getCouponById($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(CouponUpdateRequest $request, $id)
    {
        try {
            $this->couponService->updateCoupon($id, $request->validated());
            
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update coupon: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->couponService->deleteCoupon($id);
            
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete coupon: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $coupon = $this->couponService->toggleCouponStatus($id);
            $status = $coupon->status ? 'activated' : 'deactivated';
            
            return redirect()->back()
                ->with('success', "Coupon {$status} successfully.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update coupon status: ' . $e->getMessage());
        }
    }

    public function usage($id)
    {
        $coupon = $this->couponService->getCouponById($id);
        $usage = $this->couponService->getCouponUsage($id);
        
        return view('admin.coupons.usage', compact('coupon', 'usage'));
    }
}