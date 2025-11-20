<?php
// App/Http/Controllers/Admin/CategoryController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'parent_id' => $request->get('parent_id'),
        ];

        $categories = $this->categoryService->getAllCategories($filters);
        $mainCategories = $this->categoryService->getMainCategories();

        return view('admin.categories.index', compact('categories', 'filters', 'mainCategories'));
    }

    public function show(string $id): View
    {
        $category = $this->categoryService->getCategoryById($id);
        
        if (!$category) {
            abort(404);
        }

        $stats = $this->categoryService->getCategoryStats($id);

        return view('admin.categories.show', compact('category', 'stats'));
    }

    public function create(): View
    {
        $mainCategories = $this->categoryService->getMainCategories();
        return view('admin.categories.create', compact('mainCategories'));
    }

    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        try {
            $this->categoryService->createCategory($request->validated());
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(string $id): View
    {
        $category = $this->categoryService->getCategoryById($id);
        
        if (!$category) {
            abort(404);
        }

        $mainCategories = $this->categoryService->getMainCategories()
            ->where('id', '!=', $id); // Exclude current category from parent options

        return view('admin.categories.edit', compact('category', 'mainCategories'));
    }

    public function update(CategoryUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            $this->categoryService->updateCategory($id, $request->validated());
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->categoryService->deleteCategory($id);
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
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
            $this->categoryService->changeCategoryStatus($id, $request->status);
            
            $statusText = $request->status ? 'activated' : 'deactivated';
            return back()->with('success', "Category {$statusText} successfully.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function tree(): View
    {
        $categories = $this->categoryService->getCategoryTree();
        return view('admin.categories.tree', compact('categories'));
    }
}