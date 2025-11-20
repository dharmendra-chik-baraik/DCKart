<?php
// App/Repositories/ProductRepository.php
namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts(array $filters = []): LengthAwarePaginator
    {
        $query = Product::with(['vendor.user', 'category', 'images', 'variants.values']);

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sku', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('vendor.user', function ($userQuery) use ($filters) {
                      $userQuery->where('name', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status'] == 'active');
        }

        if (!empty($filters['vendor_id'])) {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['featured'])) {
            $query->where('is_featured', $filters['featured'] == 'yes');
        }

        if (!empty($filters['stock_status'])) {
            $query->where('stock_status', $filters['stock_status']);
        }

        return $query->latest()->paginate(20);
    }

    public function getProductById(string $id): ?Product
    {
        return Product::with([
            'vendor.user', 
            'category', 
            'categories',
            'images', 
            'variants.values',
            'reviews.user'
        ])->find($id);
    }

    public function createProduct(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            // Create main product
            $product = Product::create([
                'vendor_id' => $data['vendor_id'],
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'sku' => $data['sku'],
                'description' => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock' => $data['stock'] ?? 0,
                'stock_status' => $data['stock_status'] ?? 'out_of_stock',
                'weight' => $data['weight'] ?? null,
                'length' => $data['length'] ?? null,
                'width' => $data['width'] ?? null,
                'height' => $data['height'] ?? null,
                'status' => $data['status'] ?? false,
                'is_featured' => $data['is_featured'] ?? false,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'meta_keywords' => $data['meta_keywords'] ?? null,
            ]);

            // Sync additional categories
            if (!empty($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            return $product;
        });
    }

    public function updateProduct(string $id, array $data): bool
    {
        $product = $this->getProductById($id);
        
        if (!$product) {
            return false;
        }

        return DB::transaction(function () use ($product, $data) {
            // Update product
            $updated = $product->update([
                'vendor_id' => $data['vendor_id'] ?? $product->vendor_id,
                'category_id' => $data['category_id'] ?? $product->category_id,
                'name' => $data['name'] ?? $product->name,
                'slug' => $data['slug'] ?? $product->slug,
                'sku' => $data['sku'] ?? $product->sku,
                'description' => $data['description'] ?? $product->description,
                'short_description' => $data['short_description'] ?? $product->short_description,
                'price' => $data['price'] ?? $product->price,
                'sale_price' => $data['sale_price'] ?? $product->sale_price,
                'stock' => $data['stock'] ?? $product->stock,
                'stock_status' => $data['stock_status'] ?? $product->stock_status,
                'weight' => $data['weight'] ?? $product->weight,
                'length' => $data['length'] ?? $product->length,
                'width' => $data['width'] ?? $product->width,
                'height' => $data['height'] ?? $product->height,
                'status' => $data['status'] ?? $product->status,
                'is_featured' => $data['is_featured'] ?? $product->is_featured,
                'meta_title' => $data['meta_title'] ?? $product->meta_title,
                'meta_description' => $data['meta_description'] ?? $product->meta_description,
                'meta_keywords' => $data['meta_keywords'] ?? $product->meta_keywords,
            ]);

            // Sync additional categories
            if (isset($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            return $updated;
        });
    }

    public function deleteProduct(string $id): bool
    {
        $product = $this->getProductById($id);
        
        if (!$product) {
            return false;
        }

        return DB::transaction(function () use ($product) {
            // Delete related data
            $product->images()->delete();
            $product->variants()->delete();
            $product->categories()->detach();
            
            return $product->delete();
        });
    }

    public function changeProductStatus(string $id, bool $status): bool
    {
        $product = $this->getProductById($id);
        
        if (!$product) {
            return false;
        }

        return $product->update(['status' => $status]);
    }

    public function toggleFeatured(string $id): bool
    {
        $product = $this->getProductById($id);
        
        if (!$product) {
            return false;
        }

        return $product->update(['is_featured' => !$product->is_featured]);
    }

    public function updateStock(string $id, int $stock): bool
    {
        $product = $this->getProductById($id);
        
        if (!$product) {
            return false;
        }

        $stockStatus = $stock > 0 ? 'in_stock' : 'out_of_stock';

        return $product->update([
            'stock' => $stock,
            'stock_status' => $stockStatus
        ]);
    }

    public function getProductsByVendor(string $vendorId): LengthAwarePaginator
    {
        return Product::with(['vendor.user', 'category', 'images'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->paginate(20);
    }

    public function getProductsByCategory(string $categoryId): LengthAwarePaginator
    {
        return Product::with(['vendor.user', 'category', 'images'])
            ->where('category_id', $categoryId)
            ->latest()
            ->paginate(20);
    }

    // Additional methods for product images
    public function addProductImage(string $productId, array $imageData): ProductImage
    {
        $product = $this->getProductById($productId);
        
        if (!$product) {
            throw new \InvalidArgumentException('Product not found.');
        }

        return $product->images()->create($imageData);
    }

    public function deleteProductImage(string $imageId): bool
    {
        $image = ProductImage::find($imageId);
        
        if (!$image) {
            return false;
        }

        return $image->delete();
    }

    // Additional methods for product variants
    public function addProductVariant(string $productId, array $variantData): ProductVariant
    {
        $product = $this->getProductById($productId);
        
        if (!$product) {
            throw new \InvalidArgumentException('Product not found.');
        }

        return $product->variants()->create($variantData);
    }

    public function addVariantValue(string $variantId, array $valueData): ProductVariantValue
    {
        $variant = ProductVariant::find($variantId);
        
        if (!$variant) {
            throw new \InvalidArgumentException('Variant not found.');
        }

        return $variant->values()->create($valueData);
    }
}