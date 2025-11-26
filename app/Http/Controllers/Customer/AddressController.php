<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    /**
     * Display a listing of the customer's addresses.
     */
    public function index(): View
    {
        $addresses = auth()->user()->addresses()->latest()->get();
        
        return view('customer.addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create(): View
    {
        return view('customer.addresses.create');
    }

    /**
     * Store a newly created address.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line_1' => ['required', 'string'],
            'address_line_2' => ['nullable', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'pincode' => ['required', 'string', 'max:10'],
            'type' => ['required', 'in:home,work,other'],
            'is_default' => ['sometimes', 'boolean'],
        ]);

        $user = auth()->user();

        // If this is set as default, remove default from other addresses
        if ($request->has('is_default') && $request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        // If this is the first address, set it as default
        if ($user->addresses()->count() === 0) {
            $validated['is_default'] = true;
        }

        $user->addresses()->create($validated);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Address added successfully.');
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(UserAddress $address): View
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.addresses.edit', compact('address'));
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, UserAddress $address): RedirectResponse
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line_1' => ['required', 'string'],
            'address_line_2' => ['nullable', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'pincode' => ['required', 'string', 'max:10'],
            'type' => ['required', 'in:home,work,other'],
            'is_default' => ['sometimes', 'boolean'],
        ]);

        // If this is set as default, remove default from other addresses
        if ($request->has('is_default') && $request->is_default) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    /**
     * Remove the specified address.
     */
    public function destroy(UserAddress $address): RedirectResponse
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        // Don't allow deletion if it's the only address
        if (auth()->user()->addresses()->count() === 1) {
            return redirect()->route('customer.addresses.index')
                ->with('error', 'Cannot delete the only address. Please add another address first.');
        }

        // If deleting default address, set another address as default
        if ($address->is_default) {
            $newDefault = auth()->user()->addresses()
                ->where('id', '!=', $address->id)
                ->first();
            
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $address->delete();

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Address deleted successfully.');
    }

    /**
     * Set an address as default.
     */
    public function setDefault(UserAddress $address): RedirectResponse
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        // Remove default from all addresses
        auth()->user()->addresses()->update(['is_default' => false]);

        // Set this address as default
        $address->update(['is_default' => true]);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Default address updated successfully.');
    }
}