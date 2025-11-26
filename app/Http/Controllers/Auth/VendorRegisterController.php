<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VendorRegisterRequest;
use App\Services\VendorAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VendorRegisterController extends Controller
{
    public function __construct(
        private VendorAuthService $vendorAuthService
    ) {}

    /**
     * Show vendor registration form
     */
    public function showVendorRegistrationForm(): View
    {
        return view('auth.register-vendor');
    }

    /**
     * Handle vendor registration
     */
    public function register(VendorRegisterRequest $request): RedirectResponse
    {
        try {
            $user = $this->vendorAuthService->registerVendor($request->validated());

            // Log the user in after registration
            auth()->login($user);

            return redirect()->route('vendor.dashboard')
                ->with('success', 'Vendor registration submitted successfully! Your account is under review.');

        } catch (\Exception $e) {
            \Log::error('Vendor registration failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Vendor registration failed. Please try again.')
                ->withInput();
        }
    }
}