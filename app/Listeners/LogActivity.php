<?php

namespace App\Listeners;

use App\Events\ActivityLogged;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogActivity implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * Handle the event.
     */
    public function handle(ActivityLogged $event): void
    {
        ActivityLog::create([
            'user_id' => $event->user->id,
            'action' => $event->action,
            'module' => $event->module,
            'description' => $event->description,
            'ip_address' => $event->ipAddress,
            'created_at' => now(),
            'updated_at' => now(), // Add this if your table has updated_at
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(ActivityLogged $event, \Throwable $exception): void
    {
        // Log the failure or send notification
        \Log::error('Failed to log activity: ' . $exception->getMessage(), [
            'user_id' => $event->user->id,
            'action' => $event->action,
            'module' => $event->module,
        ]);
    }
}