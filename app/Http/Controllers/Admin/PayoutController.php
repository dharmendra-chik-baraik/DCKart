<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PayoutProcessRequest;
use App\Http\Requests\Admin\PayoutCreateRequest;
use App\Services\PayoutService;
use App\Models\VendorProfile;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    protected $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'vendor_id' => $request->get('vendor_id'),
            'search' => $request->get('search'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $payouts = $this->payoutService->getAllPayouts($filters);
        $stats = $this->payoutService->getPayoutStats();
        $vendors = VendorProfile::where('status', 'approved')->get();

        return view('admin.payouts.index', compact('payouts', 'stats', 'vendors', 'filters'));
    }

    public function create()
    {
        $vendors = VendorProfile::with(['user'])->where('status', 'approved')->get();
        $vendorsWithEarnings = $this->payoutService->getVendorsWithEarnings();
        
        return view('admin.payouts.create', compact('vendors', 'vendorsWithEarnings'));
    }

    public function store(PayoutCreateRequest $request)
    {
        try {
            $payout = $this->payoutService->createPayout([
                'vendor_id' => $request->vendor_id,
                'amount' => $request->amount,
                'status' => 'pending',
                'remarks' => $request->remarks,
            ]);

            return redirect()->route('admin.payouts.show', $payout->id)
                ->with('success', 'Payout created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create payout: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $payout = $this->payoutService->getPayout($id);
        $vendorEarnings = $this->payoutService->getVendorEarnings($payout->vendor_id);
        
        return view('admin.payouts.show', compact('payout', 'vendorEarnings'));
    }

    public function updateStatus(PayoutProcessRequest $request, $id)
    {
        try {
            $payout = $this->payoutService->updatePayoutStatus(
                $id, 
                $request->status, 
                $request->transaction_id,
                $request->remarks
            );

            return redirect()->route('admin.payouts.show', $id)
                ->with('success', 'Payout status updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update payout status: ' . $e->getMessage());
        }
    }

    public function bulkProcess(Request $request)
    {
        $request->validate([
            'payout_ids' => 'required|array',
            'payout_ids.*' => 'exists:vendor_payouts,id',
            'status' => 'required|in:processed,failed',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        try {
            $results = $this->payoutService->processBulkPayouts(
                $request->payout_ids,
                $request->status,
                $request->transaction_id
            );

            $successCount = count(array_filter($results, function($result) {
                return $result['status'] === 'success';
            }));

            $errorCount = count($request->payout_ids) - $successCount;

            $message = "Successfully processed {$successCount} payouts.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} payouts failed to process.";
            }

            return redirect()->route('admin.payouts.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process payouts: ' . $e->getMessage());
        }
    }

    public function vendorEarnings($vendorId)
    {
        try {
            $vendor = VendorProfile::with(['user'])->findOrFail($vendorId);
            $earnings = $this->payoutService->getVendorEarnings($vendorId);
            
            return response()->json([
                'vendor' => $vendor,
                'earnings' => $earnings,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch vendor earnings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePayout(Request $request, $vendorId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'remarks' => 'nullable|string|max:500',
        ]);

        try {
            $payout = $this->payoutService->generatePayoutForVendor(
                $vendorId,
                $request->amount,
                $request->remarks
            );

            return redirect()->route('admin.payouts.show', $payout->id)
                ->with('success', 'Payout generated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate payout: ' . $e->getMessage());
        }
    }
}