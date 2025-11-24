<?php

namespace App\Repositories;

use App\Interfaces\PageRepositoryInterface;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PageRepository implements PageRepositoryInterface
{
    public function getAll(): Collection
    {
        return Page::latest()->get();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Page::latest()->paginate($perPage);
    }

    public function getFiltered(array $filters = []): LengthAwarePaginator
    {
        $query = Page::query();

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('slug', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(15);
    }

    public function findById(string $id): ?Page
    {
        return Page::find($id);
    }

    public function findBySlug(string $slug): ?Page
    {
        return Page::where('slug', $slug)->first();
    }

    public function create(array $data): Page
    {
        return Page::create($data);
    }

    public function update(Page $page, array $data): bool
    {
        return $page->update($data);
    }

    public function delete(Page $page): bool
    {
        return $page->delete();
    }

    public function toggleStatus(Page $page): bool
    {
        return $page->update(['status' => !$page->status]);
    }
}