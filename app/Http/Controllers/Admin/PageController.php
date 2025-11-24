<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Http\Requests\Admin\PageStoreRequest;
use App\Http\Requests\Admin\PageUpdateRequest;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function index(Request $request)
    {
        $pages = $this->pageService->getAllPages($request->all());
        
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(PageStoreRequest $request)
    {
        try {
            $this->pageService->createPage($request->validated());
            return redirect()->route('admin.pages.index')
                ->with('success', 'Page created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating page: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageUpdateRequest $request, Page $page)
    {
        try {
            $this->pageService->updatePage($page, $request->validated());
            return redirect()->route('admin.pages.index')
                ->with('success', 'Page updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating page: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Page $page)
    {
        try {
            $this->pageService->deletePage($page);
            return redirect()->route('admin.pages.index')
                ->with('success', 'Page deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting page: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Page $page)
    {
        try {
            $this->pageService->toggleStatus($page);
            return response()->json([
                'success' => true,
                'message' => 'Page status updated successfully.',
                'status' => $page->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating page status.'
            ], 500);
        }
    }
}