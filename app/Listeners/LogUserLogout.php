<?php

namespace App\Listeners;

use App\Events\ActivityLogged;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class LogUserLogout implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        // Create a unique key for this logout event to prevent duplicates
        $cacheKey = 'user_logout_' . $event->user->id . '_' . now()->timestamp;
        
        // Only log if this event hasn't been processed recently
        if (!Cache::has($cacheKey)) {
            event(new ActivityLogged(
                $event->user,
                'logout',
                'auth',
                'User logged out'
            ));
            
            // Cache for 5 seconds to prevent duplicates
            Cache::put($cacheKey, true, 5);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Logout $event, \Throwable $exception): void
    {
        \Log::error('Failed to log user logout: ' . $exception->getMessage());
    }
}