<?php
// App/Interfaces/CategoryRepositoryInterface.php
namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function getAllCategories(array $filters = []): LengthAwarePaginator;
    public function getCategoryById(string $id): ?Category;
    public function createCategory(array $data): Category;
    public function updateCategory(string $id, array $data): bool;
    public function deleteCategory(string $id): bool;
    public function changeCategoryStatus(string $id, bool $status): bool;
    public function getMainCategories(): Collection;
    public function getCategoryTree(): Collection;
}