<?php
// App/Services/CategoryService.php
namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(protected CategoryRepositoryInterface $categoryRepository) {}

    public function getAllCategories(array $filters = []): LengthAwarePaginator
    {
        return $this->categoryRepository->getAllCategories($filters);
    }

    public function getCategoryById(string $id): ?Category
    {
        return $this->categoryRepository->getCategoryById($id);
    }

    public function createCategory(array $data): Category
    {
        // Validate required fields
        $required = ['name', 'slug'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("The {$field} field is required.");
            }
        }

        // Check if slug is unique
        if (Category::where('slug', $data['slug'])->exists()) {
            throw new \InvalidArgumentException('The slug has already been taken.');
        }

        return $this->categoryRepository->createCategory($data);
    }

    public function updateCategory(string $id, array $data): bool
    {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            throw new \InvalidArgumentException('Category not found.');
        }

        // Validate slug uniqueness
        if (isset($data['slug']) && 
            Category::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
            throw new \InvalidArgumentException('The slug has already been taken.');
        }

        return $this->categoryRepository->updateCategory($id, $data);
    }

    public function deleteCategory(string $id): bool
    {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            throw new \InvalidArgumentException('Category not found.');
        }

        return $this->categoryRepository->deleteCategory($id);
    }

    public function changeCategoryStatus(string $id, bool $status): bool
    {
        return $this->categoryRepository->changeCategoryStatus($id, $status);
    }

    public function getMainCategories(): Collection
    {
        return $this->categoryRepository->getMainCategories();
    }

    public function getCategoryTree(): Collection
    {
        return $this->categoryRepository->getCategoryTree();
    }

    public function getCategoryStats(string $id): array
    {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            return [];
        }

        return [
            'total_products' => $category->products->count(),
            'total_subcategories' => $category->children->count(),
            'total_descendants' => $this->countDescendants($category),
        ];
    }

    private function countDescendants(Category $category): int
    {
        $count = 0;
        foreach ($category->children as $child) {
            $count += 1 + $this->countDescendants($child);
        }
        return $count;
    }
}