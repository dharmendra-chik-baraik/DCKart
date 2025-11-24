<?php

namespace App\Interfaces;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PageRepositoryInterface
{
    public function getAll(): Collection;
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;
    public function getFiltered(array $filters = []): LengthAwarePaginator;
    public function findById(string $id): ?Page;
    public function findBySlug(string $slug): ?Page;
    public function create(array $data): Page;
    public function update(Page $page, array $data): bool;
    public function delete(Page $page): bool;
    public function toggleStatus(Page $page): bool;
}