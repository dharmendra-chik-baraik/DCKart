<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        
        if ($this->authService->login($credentials, $request->remember)) {
            $user = $this->authService->getAuthenticatedUser();
            return $this->authenticated($request, $user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    protected function authenticated(Request $request, $user)
    {
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'vendor' => redirect()->route('vendor.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}