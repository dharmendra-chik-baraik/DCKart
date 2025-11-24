<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityLogged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $action;
    public $module;
    public $description;
    public $ipAddress;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $action, string $module, ?string $description = null, ?string $ipAddress = null)
    {
        $this->user = $user;
        $this->action = $action;
        $this->module = $module;
        $this->description = $description;
        $this->ipAddress = $ipAddress ?: request()->ip();
    }
}