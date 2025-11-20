<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Log for debugging
            \Log::info('Redirecting to login from Authenticate middleware', [
                'url' => $request->url(),
                'user' => auth()->check() ? auth()->user()->id : 'none'
            ]);
            
            return route('login');
        }
        
        return null;
    }
}