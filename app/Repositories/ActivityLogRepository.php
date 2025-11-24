<?php

namespace App\Repositories;

use App\Interfaces\ActivityLogRepositoryInterface;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function getAll(): Collection
    {
        return ActivityLog::with('user')->latest()->get();
    }

    public function getPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return ActivityLog::with('user')->latest()->paginate($perPage);
    }

    public function getFiltered(array $filters = []): LengthAwarePaginator
    {
        $query = ActivityLog::with(['user' => function($query) {
            $query->select('id', 'name', 'email', 'role');
        }]);

        // Search filter
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('action', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('module', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('ip_address', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('user', function($userQuery) use ($filters) {
                      $userQuery->where('name', 'like', '%' . $filters['search'] . '%')
                               ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        // Module filter
        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        // Action filter
        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        // User filter
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }

        return $query->latest()->paginate(20);
    }

    public function findById(string $id): ?ActivityLog
    {
        return ActivityLog::with('user')->find($id);
    }

    public function getByUser(string $userId): Collection
    {
        return ActivityLog::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function getByModule(string $module): Collection
    {
        return ActivityLog::with('user')
            ->where('module', $module)
            ->latest()
            ->get();
    }

    public function delete(ActivityLog $activityLog): bool
    {
        return $activityLog->delete();
    }

    public function clearOldLogs(int $days = 30): int
    {
        $cutoffDate = Carbon::now()->subDays($days);
        return ActivityLog::where('created_at', '<', $cutoffDate)->delete();
    }

    public function getModules(): array
    {
        return ActivityLog::distinct()->pluck('module')->toArray();
    }

    public function getActions(): array
    {
        return ActivityLog::distinct()->pluck('action')->toArray();
    }
}