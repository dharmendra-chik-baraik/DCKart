<?php

namespace App\Services;

use App\Interfaces\ActivityLogRepositoryInterface;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogService
{
    protected $activityLogRepository;

    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    public function getActivityLogs(array $filters = []): LengthAwarePaginator
    {
        return $this->activityLogRepository->getFiltered($filters);
    }

    public function getActivityLog(string $id): ?ActivityLog
    {
        return $this->activityLogRepository->findById($id);
    }

    public function deleteActivityLog(ActivityLog $activityLog): bool
    {
        return $this->activityLogRepository->delete($activityLog);
    }

    public function clearOldLogs(int $days = 30): int
    {
        return $this->activityLogRepository->clearOldLogs($days);
    }

    public function getFilterOptions(): array
    {
        return [
            'modules' => $this->activityLogRepository->getModules(),
            'actions' => $this->activityLogRepository->getActions(),
            'users' => \App\Models\User::whereIn('role', ['admin', 'vendor'])
                ->select('id', 'name', 'email')
                ->get()
        ];
    }

    /**
     * Log activity - This can be used throughout your application
     */
    public function logActivity(
        User $user,
        string $action,
        string $module,
        ?string $description = null,
        ?string $ipAddress = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => $ipAddress ?: request()->ip(),
            'created_at' => now(),
        ]);
    }
}