<?php
// App/Interfaces/ProductRepositoryInterface.php
namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function getAllProducts(array $filters = []): LengthAwarePaginator;
    public function getProductById(string $id): ?Product;
    public function createProduct(array $data): Product;
    public function updateProduct(string $id, array $data): bool;
    public function deleteProduct(string $id): bool;
    public function changeProductStatus(string $id, bool $status): bool;
    public function toggleFeatured(string $id): bool;
    public function updateStock(string $id, int $stock): bool;
    public function getProductsByVendor(string $vendorId): LengthAwarePaginator;
    public function getProductsByCategory(string $categoryId): LengthAwarePaginator;
}