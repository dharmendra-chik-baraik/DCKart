<?php

namespace App\Interfaces;

use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ActivityLogRepositoryInterface
{
    public function getAll(): Collection;
    public function getPaginated(int $perPage = 20): LengthAwarePaginator;
    public function getFiltered(array $filters = []): LengthAwarePaginator;
    public function findById(string $id): ?ActivityLog;
    public function getByUser(string $userId): Collection;
    public function getByModule(string $module): Collection;
    public function delete(ActivityLog $activityLog): bool;
    public function clearOldLogs(int $days = 30): int;
    public function getModules(): array;
    public function getActions(): array;
}