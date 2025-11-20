<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role === 'admin') {
            return $next($request);
        }
        $userRole = auth()->user()->role;

        return match ($userRole) {
            'customer' => redirect()->route('customer.dashboard'),
            'vendor' => redirect()->route('vendor.dashboard'),
            default => abort(403, 'Unauthorized access.')
        };
    }
}
