<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\ProductService;
use App\Services\Frontend\CategoryService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    protected $categoryService;

    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $filters = [
            'category' => $request->get('category'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
        ];

        $sort = $request->get('sort', 'latest');
        $products = $this->productService->getProducts($filters, $sort);
        $categories = $this->categoryService->getAllCategories();

        return view('frontend.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = $this->productService->getProduct($slug);
        $relatedProducts = $this->productService->getRelatedProducts(
            $product->id, 
            $product->category_id
        );

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $products = $this->productService->searchProducts($query);

        return view('frontend.products.search', compact('products', 'query'));
    }

    public function byCategory($slug)
    {
        $data = $this->categoryService->getCategoryWithProducts($slug);
        return view('frontend.products.by-category', $data);
    }
}