<?php
// App/Services/ProductService.php
namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(protected ProductRepositoryInterface $productRepository) {}

    public function getAllProducts(array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->getAllProducts($filters);
    }

    public function getProductById(string $id): ?Product
    {
        return $this->productRepository->getProductById($id);
    }

    public function createProduct(array $data): Product
    {
        // Validate required fields
        $required = ['vendor_id', 'category_id', 'name', 'slug', 'sku', 'price'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("The {$field} field is required.");
            }
        }

        // Check if slug is unique
        if (Product::where('slug', $data['slug'])->exists()) {
            throw new \InvalidArgumentException('The product slug has already been taken.');
        }

        // Check if SKU is unique
        if (Product::where('sku', $data['sku'])->exists()) {
            throw new \InvalidArgumentException('The product SKU has already been taken.');
        }

        // Validate price
        if ($data['price'] <= 0) {
            throw new \InvalidArgumentException('Product price must be greater than 0.');
        }

        // Validate sale price if provided
        if (isset($data['sale_price']) && $data['sale_price'] >= $data['price']) {
            throw new \InvalidArgumentException('Sale price must be less than regular price.');
        }

        return $this->productRepository->createProduct($data);
    }

    public function updateProduct(string $id, array $data): bool
    {
        $product = $this->getProductById($id);
        
        if (!$product) {
            throw new \InvalidArgumentException('Product not found.');
        }

        // Validate slug uniqueness
        if (isset($data['slug']) && 
            Product::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
            throw new \InvalidArgumentException('The product slug has already been taken.');
        }

        // Validate SKU uniqueness
        if (isset($data['sku']) && 
            Product::where('sku', $data['sku'])->where('id', '!=', $id)->exists()) {
            throw new \InvalidArgumentException('The product SKU has already been taken.');
        }

        // Validate price
        if (isset($data['price']) && $data['price'] <= 0) {
            throw new \InvalidArgumentException('Product price must be greater than 0.');
        }

        // Validate sale price if provided
        if (isset($data['sale_price']) && $data['sale_price'] >= $data['price']) {
            throw new \InvalidArgumentException('Sale price must be less than regular price.');
        }

        return $this->productRepository->updateProduct($id, $data);
    }

    public function deleteProduct(string $id): bool
    {
        $product = $this->getProductById($id);
        
        if (!$product) {
            throw new \InvalidArgumentException('Product not found.');
        }

        return $this->productRepository->deleteProduct($id);
    }

    public function changeProductStatus(string $id, bool $status): bool
    {
        return $this->productRepository->changeProductStatus($id, $status);
    }

    public function toggleFeatured(string $id): bool
    {
        return $this->productRepository->toggleFeatured($id);
    }

    public function updateStock(string $id, int $stock): bool
    {
        if ($stock < 0) {
            throw new \InvalidArgumentException('Stock quantity cannot be negative.');
        }

        return $this->productRepository->updateStock($id, $stock);
    }

    public function getProductsByVendor(string $vendorId): LengthAwarePaginator
    {
        return $this->productRepository->getProductsByVendor($vendorId);
    }

    public function getProductsByCategory(string $categoryId): LengthAwarePaginator
    {
        return $this->productRepository->getProductsByCategory($categoryId);
    }

    public function getProductStats(string $id): array
    {
        $product = $this->getProductById($id);
        
        if (!$product) {
            return [];
        }

        return [
            'total_orders' => $product->orderItems->count(),
            'total_revenue' => $product->orderItems->sum('price'),
            'total_reviews' => $product->reviews->count(),
            'average_rating' => $product->average_rating,
            'total_wishlists' => $product->wishlists->count(),
            'total_carts' => $product->carts->count(),
        ];
    }

    // Image management
    public function addProductImage(string $productId, array $imageData): \App\Models\ProductImage
    {
        return $this->productRepository->addProductImage($productId, $imageData);
    }

    public function deleteProductImage(string $imageId): bool
    {
        return $this->productRepository->deleteProductImage($imageId);
    }
}