<?php
// App/Repositories/CategoryRepository.php
namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories(array $filters = []): LengthAwarePaginator
    {
        $query = Category::with(['parent', 'children', 'products']);

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('slug', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status'] == 'active');
        }

        if (!empty($filters['parent_id'])) {
            if ($filters['parent_id'] === 'null') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $filters['parent_id']);
            }
        }

        return $query->latest()->paginate(20);
    }

    public function getCategoryById(string $id): ?Category
    {
        return Category::with(['parent', 'children', 'products'])->find($id);
    }

    public function createCategory(array $data): Category
    {
        return Category::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'parent_id' => $data['parent_id'] ?? null,
            'icon' => $data['icon'] ?? null,
            'image' => $data['image'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? true,
        ]);
    }

    public function updateCategory(string $id, array $data): bool
    {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            return false;
        }

        // Prevent category from being its own parent
        if (isset($data['parent_id']) && $data['parent_id'] == $id) {
            throw new \InvalidArgumentException('Category cannot be its own parent.');
        }

        return $category->update($data);
    }

    public function deleteCategory(string $id): bool
    {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            return false;
        }

        // Check if category has products
        if ($category->products->count() > 0) {
            throw new \Exception('Cannot delete category that has products. Please reassign products first.');
        }

        // Check if category has subcategories
        if ($category->children->count() > 0) {
            throw new \Exception('Cannot delete category that has subcategories. Please delete or reassign subcategories first.');
        }

        return $category->delete();
    }

    public function changeCategoryStatus(string $id, bool $status): bool
    {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            return false;
        }

        return $category->update(['status' => $status]);
    }

    public function getMainCategories(): Collection
    {
        return Category::whereNull('parent_id')
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }

    public function getCategoryTree(): Collection
    {
        return Category::with(['children.children'])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
    }
}