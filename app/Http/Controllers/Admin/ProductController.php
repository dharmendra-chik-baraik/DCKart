<?php
// App/Http/Controllers/Admin/ProductController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Models\Category;
use App\Models\VendorProfile;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService) {}

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'vendor_id' => $request->get('vendor_id'),
            'category_id' => $request->get('category_id'),
            'featured' => $request->get('featured'),
            'stock_status' => $request->get('stock_status'),
        ];

        $products = $this->productService->getAllProducts($filters);
        $vendors = VendorProfile::with('user')->get();
        $categories = Category::where('status', true)->get();

        return view('admin.products.index', compact('products', 'filters', 'vendors', 'categories'));
    }

    public function show(string $id): View
    {
        $product = $this->productService->getProductById($id);
        
        if (!$product) {
            abort(404);
        }

        $stats = $this->productService->getProductStats($id);

        return view('admin.products.show', compact('product', 'stats'));
    }

    public function create(): View
    {
        $vendors = VendorProfile::with('user')->where('status', 'approved')->get();
        $categories = Category::where('status', true)->get();
        
        return view('admin.products.create', compact('vendors', 'categories'));
    }

    public function store(ProductStoreRequest $request): RedirectResponse
    {
        try {
            $this->productService->createProduct($request->validated());
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(string $id): View
    {
        $product = $this->productService->getProductById($id);
        
        if (!$product) {
            abort(404);
        }

        $vendors = VendorProfile::with('user')->where('status', 'approved')->get();
        $categories = Category::where('status', true)->get();

        return view('admin.products.edit', compact('product', 'vendors', 'categories'));
    }

    public function update(ProductUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            $this->productService->updateProduct($id, $request->validated());
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->productService->deleteProduct($id);
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function changeStatus(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        try {
            $this->productService->changeProductStatus($id, $request->status);
            
            $statusText = $request->status ? 'activated' : 'deactivated';
            return back()->with('success', "Product {$statusText} successfully.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function toggleFeatured(string $id): RedirectResponse
    {
        try {
            $this->productService->toggleFeatured($id);
            
            return back()->with('success', 'Product featured status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStock(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        try {
            $this->productService->updateStock($id, $request->stock);
            
            return back()->with('success', 'Product stock updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}