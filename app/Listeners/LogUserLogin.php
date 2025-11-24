<?php

namespace App\Listeners;

use App\Events\ActivityLogged;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class LogUserLogin implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Create a unique key for this login event to prevent duplicates
        $cacheKey = 'user_login_' . $event->user->id . '_' . now()->timestamp;
        
        // Only log if this event hasn't been processed recently
        if (!Cache::has($cacheKey)) {
            event(new ActivityLogged(
                $event->user,
                'login',
                'auth',
                'User logged in successfully'
            ));
            
            // Cache for 5 seconds to prevent duplicates
            Cache::put($cacheKey, true, 5);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Login $event, \Throwable $exception): void
    {
        \Log::error('Failed to log user login: ' . $exception->getMessage());
    }
}