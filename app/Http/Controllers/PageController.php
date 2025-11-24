<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of active pages for frontend.
     */
    public function publicIndex()
    {
        $pages = Page::where('status', true)
                    ->orderBy('title')
                    ->get();

        return view('pages.index', compact('pages'));
    }

    /**
     * Display the specified page for frontend.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
                   ->where('status', true)
                   ->firstOrFail();

        return view('pages.show', compact('page'));
    }
}