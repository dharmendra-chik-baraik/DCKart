<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index (): View
    {
        return view('customer.profile.show', [
            'user' => auth()->user()
        ]);
    }
    /**
     * Display the customer's profile form.
     */
    public function edit(): View
    {
        return view('customer.profile.edit', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the customer's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->route('customer.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the customer's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('customer.profile.edit')
            ->with('success', 'Password updated successfully.');
    }
}