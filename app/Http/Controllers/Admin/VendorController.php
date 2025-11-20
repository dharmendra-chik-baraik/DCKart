<?php
// App/Http/Controllers/Admin/VendorController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VendorStoreRequest;
use App\Http\Requests\Admin\VendorUpdateRequest;
use App\Http\Requests\Admin\VendorStatusRequest;
use App\Services\VendorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function __construct(protected VendorService $vendorService) {}

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'verified' => $request->get('verified'),
        ];

        $vendors = $this->vendorService->getAllVendors($filters);

        return view('admin.vendors.index', compact('vendors', 'filters'));
    }

    public function show(string $id): View
    {
        $vendor = $this->vendorService->getVendorById($id);
        
        if (!$vendor) {
            abort(404);
        }

        $stats = $this->vendorService->getVendorStats($id);

        return view('admin.vendors.show', compact('vendor', 'stats'));
    }

    public function create(): View
    {
        return view('admin.vendors.create');
    }

    public function store(VendorStoreRequest $request): RedirectResponse
    {
        try {
            $this->vendorService->createVendor($request->validated());
            
            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(string $id): View
    {
        $vendor = $this->vendorService->getVendorById($id);
        
        if (!$vendor) {
            abort(404);
        }

        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(VendorUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            $this->vendorService->updateVendor($id, $request->validated());
            
            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->vendorService->deleteVendor($id);
            
            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function changeStatus(VendorStatusRequest $request, string $id): RedirectResponse
    {
        try {
            $this->vendorService->changeVendorStatus($id, $request->validated()['status']);
            
            return back()->with('success', 'Vendor status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function toggleVerification(string $id): RedirectResponse
    {
        try {
            $this->vendorService->toggleVerification($id);
            
            return back()->with('success', 'Vendor verification status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}