<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\AuthServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    /**
     * Show registration form
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        try {
            Log::info('Registration started', ['email' => $request->email]);

            // Validate the request
            $validated = $request->validated();
            Log::info('Validation passed', $validated);

            $user = $this->authService->registerUser($validated);
            Log::info('User created', ['user_id' => $user->id]);

            auth()->login($user);
            Log::info('User logged in');

            return redirect()->route('home')
                ->with('success', 'Registration successful! Welcome to our platform.');

        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $request->email ?? 'unknown',
            ]);

            return redirect()->back()
                ->with('error', 'Registration failed: '.$e->getMessage())
                ->withInput();
        }
    }
}
