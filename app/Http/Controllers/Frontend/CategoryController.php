<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('frontend.categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $data = $this->categoryService->getCategoryWithProducts($slug);
        return view('frontend.categories.show', $data);
    }
}